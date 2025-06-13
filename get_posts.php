<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    exit('Not logged in');
}

$user_id = $_SESSION['user_id'];

// Get posts from user and their friends
$stmt = $pdo->prepare("
    SELECT p.*, u.username 
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.user_id IN (
        SELECT friend_id 
        FROM friendships 
        WHERE user_id = ?
    ) OR p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$user_id, $user_id]);
$posts = $stmt->fetchAll();

if (empty($posts)) {
    echo '<p>No posts to display</p>';
} else {
    foreach ($posts as $post) {
        echo '<div class="post">';
        echo '<div class="post-header">';
        echo '<strong>' . htmlspecialchars($post['username']) . '</strong>';
        echo '<span class="post-time">' . date('F j, Y, g:i a', strtotime($post['created_at'])) . '</span>';
        echo '</div>';
        echo '<div class="post-content">' . nl2br(htmlspecialchars($post['content'])) . '</div>';
        echo '<div class="post-actions">';
        echo '<button class="like-btn" data-post-id="' . $post['id'] . '">Like</button>';
        echo '<button class="comment-btn" data-post-id="' . $post['id'] . '">Comment</button>';
        echo '</div>';
        echo '</div>';
    }
}
?> 