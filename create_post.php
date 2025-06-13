<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request method');
}

$content = trim($_POST['content']);
$user_id = $_SESSION['user_id'];

if (empty($content)) {
    exit('Post content cannot be empty');
}

$stmt = $pdo->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
$stmt->execute([$user_id, $content]);

echo 'success';
?> 