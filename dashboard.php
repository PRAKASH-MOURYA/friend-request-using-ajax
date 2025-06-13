<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Social Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #1877f2;
            --hover-color: #e4e6eb;
        }

        body {
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            position: fixed;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
            overflow-y: auto;
        }

        .profile-section {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e4e6eb;
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid var(--primary-color);
        }

        .profile-name {
            font-weight: bold;
            margin: 10px 0;
            color: #1c1e21;
        }

        .nav-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: #1c1e21;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .nav-item:hover {
            background-color: var(--hover-color);
            text-decoration: none;
            color: var(--primary-color);
        }

        .nav-item.active {
            background-color: var(--hover-color);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }

        .nav-item i {
            width: 25px;
            margin-right: 10px;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            flex: 1;
        }

        .content-header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .notification-badge {
            background-color: #e41e3f;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 5px;
        }

        .message-badge {
            background-color: #e41e3f;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-section">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>&background=random" 
                     alt="Profile Picture" 
                     class="profile-pic">
                <div class="profile-name"><?php echo htmlspecialchars($user['username']); ?></div>
            </div>

            <a href="dashboard.php" class="nav-item active">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="friends.php" class="nav-item">
                <i class="fas fa-user-friends"></i> Friends
            </a>
            <a href="messages.php" class="nav-item">
                <i class="fas fa-envelope"></i> Messages
                <span class="message-badge">3</span>
            </a>
            <a href="notifications.php" class="nav-item">
                <i class="fas fa-bell"></i> Notifications
                <span class="notification-badge">5</span>
            </a>
            <a href="create_post.php" class="nav-item">
                <i class="fas fa-plus-circle"></i> Create Post
            </a>
            <a href="settings.php" class="nav-item">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="group_chat.php" class="nav-item">
                <i class="fas fa-users"></i> Group Chat
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                <p>Here's what's happening in your social network.</p>
            </div>

            <!-- Feed Content -->
            <div class="feed-container">
                <!-- Posts will be loaded here -->
                <div id="posts">
                    <!-- Posts will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load posts when page loads
        $(document).ready(function() {
            loadPosts();
        });

        function loadPosts() {
            $.ajax({
                url: 'get_posts.php',
                method: 'GET',
                success: function(response) {
                    $('#posts').html(response);
                }
            });
        }
    </script>
</body>
</html> 