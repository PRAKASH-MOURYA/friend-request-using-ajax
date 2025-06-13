<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$user_id = $_SESSION['user_id'];

// Get pending friend requests
$stmt = $pdo->prepare("
    SELECT fr.id, u.username 
    FROM friend_requests fr 
    JOIN users u ON fr.sender_id = u.id 
    WHERE fr.receiver_id = ? AND fr.status = 'pending'
");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();

if (empty($requests)) {
    echo '<p>No pending friend requests</p>';
} else {
    foreach ($requests as $request) {
        echo '<div class="friend-request">';
        echo '<span>' . htmlspecialchars($request['username']) . '</span>';
        echo '<div class="request-actions">';
        echo '<button class="accept-btn" data-request-id="' . $request['id'] . '">Accept</button>';
        echo '<button class="reject-btn" data-request-id="' . $request['id'] . '">Reject</button>';
        echo '</div>';
        echo '</div>';
    }
}
?> 