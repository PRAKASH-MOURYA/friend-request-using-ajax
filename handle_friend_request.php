<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request method');
}

$request_id = $_POST['request_id'];
$action = $_POST['action'];
$user_id = $_SESSION['user_id'];

// Verify the request belongs to the current user
$stmt = $pdo->prepare("SELECT * FROM friend_requests WHERE id = ? AND receiver_id = ?");
$stmt->execute([$request_id, $user_id]);
$request = $stmt->fetch();

if (!$request) {
    exit('Invalid request');
}

if ($action === 'accept') {
    // Update friend request status
    $stmt = $pdo->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
    $stmt->execute([$request_id]);

    // Create friendship in both directions
    $stmt = $pdo->prepare("INSERT INTO friendships (user_id, friend_id) VALUES (?, ?), (?, ?)");
    $stmt->execute([$user_id, $request['sender_id'], $request['sender_id'], $user_id]);
} elseif ($action === 'reject') {
    // Update friend request status
    $stmt = $pdo->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$request_id]);
}

echo 'success';
?> 