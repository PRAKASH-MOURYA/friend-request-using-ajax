<?php
require_once 'config.php';

class FriendRequest {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Search users
    public function searchUsers($searchTerm, $currentUserId) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.email 
            FROM users u 
            WHERE (u.username LIKE :search OR u.email LIKE :search)
            AND u.id != :currentUserId
            AND u.id NOT IN (
                SELECT receiver_id FROM friend_requests 
                WHERE sender_id = :currentUserId AND status = 'blocked'
                UNION
                SELECT sender_id FROM friend_requests 
                WHERE receiver_id = :currentUserId AND status = 'blocked'
            )
            LIMIT 10
        ");
        
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Send friend request
    public function sendRequest($senderId, $receiverId) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO friend_requests (sender_id, receiver_id, status)
                VALUES (:sender_id, :receiver_id, 'pending')
                ON DUPLICATE KEY UPDATE status = 'pending'
            ");
            
            $stmt->bindParam(':sender_id', $senderId);
            $stmt->bindParam(':receiver_id', $receiverId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Accept friend request
    public function acceptRequest($requestId, $receiverId) {
        $stmt = $this->pdo->prepare("
            UPDATE friend_requests 
            SET status = 'accepted' 
            WHERE id = :requestId AND receiver_id = :receiverId
        ");
        
        $stmt->bindParam(':requestId', $requestId);
        $stmt->bindParam(':receiverId', $receiverId);
        return $stmt->execute();
    }

    // Block user
    public function blockUser($userId, $blockedUserId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO friend_requests (sender_id, receiver_id, status)
            VALUES (:userId, :blockedUserId, 'blocked')
            ON DUPLICATE KEY UPDATE status = 'blocked'
        ");
        
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':blockedUserId', $blockedUserId);
        return $stmt->execute();
    }

    // Get friend requests
    public function getFriendRequests($userId) {
        $stmt = $this->pdo->prepare("
            SELECT fr.*, u.username, u.email
            FROM friend_requests fr
            JOIN users u ON fr.sender_id = u.id
            WHERE fr.receiver_id = :userId AND fr.status = 'pending'
        ");
        
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get friends list
    public function getFriendsList($userId) {
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.username, u.email
            FROM friend_requests fr
            JOIN users u ON (
                CASE 
                    WHEN fr.sender_id = :userId THEN fr.receiver_id = u.id
                    ELSE fr.sender_id = u.id
                END
            )
            WHERE (fr.sender_id = :userId OR fr.receiver_id = :userId)
            AND fr.status = 'accepted'
        ");
        
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 