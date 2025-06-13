<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Platform</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar">
        <div class="logo">Social Media</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
            <a href="friends.php">Friends</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="sidebar">
            <div class="user-info">
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
            </div>
            <div class="friend-requests">
                <h4>Friend Requests</h4>
                <div id="friend-requests-list">
                    <!-- Friend requests will be loaded here via AJAX -->
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="post-form">
                <form id="post-form">
                    <textarea placeholder="What's on your mind?" name="content"></textarea>
                    <button type="submit">Post</button>
                </form>
            </div>
            <div id="posts">
                <!-- Posts will be loaded here -->
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html> 