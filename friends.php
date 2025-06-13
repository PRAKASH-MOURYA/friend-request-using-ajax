<?php
require_once 'config.php';
require_once 'friend_requests.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$friendRequest = new FriendRequest($pdo);
$currentUserId = $_SESSION['user_id'];

// handle friend request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'send_request':
                if (isset($_POST['receiver_id'])) {
                    $friendRequest->sendRequest($currentUserId, $_POST['receiver_id']);
                }
                break;
            case 'accept_request':
                if (isset($_POST['request_id'])) {
                    $friendRequest->acceptRequest($_POST['request_id'], $currentUserId);
                }
                break;
            case 'block_user':
                if (isset($_POST['user_id'])) {
                    $friendRequest->blockUser($currentUserId, $_POST['user_id']);
                }
                break;
        }
    }
}


$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchResults = $friendRequest->searchUsers($_GET['search'], $currentUserId);
}


$pendingRequests = $friendRequest->getFriendRequests($currentUserId);


$friendsList = $friendRequest->getFriendsList($currentUserId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Friend Management</h2>
 
        <form class="mb-4" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <?php if (!empty($searchResults)): ?>
        <div class="card mb-4">
            <div class="card-header">Search Results</div>
            <div class="card-body">
                <?php foreach ($searchResults as $user): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                        <small class="text-muted">(<?php echo htmlspecialchars($user['email']); ?>)</small>
                    </div>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="send_request">
                        <input type="hidden" name="receiver_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-primary">Send Request</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($pendingRequests)): ?>
        <div class="card mb-4">
            <div class="card-header">Pending Friend Requests</div>
            <div class="card-body">
                <?php foreach ($pendingRequests as $request): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong><?php echo htmlspecialchars($request['username']); ?></strong>
                        <small class="text-muted">(<?php echo htmlspecialchars($request['email']); ?>)</small>
                    </div>
                    <div>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="accept_request">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success">Accept</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="block_user">
                            <input type="hidden" name="user_id" value="<?php echo $request['sender_id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Block</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">Friends List</div>
            <div class="card-body">
                <?php if (!empty($friendsList)): ?>
                    <?php foreach ($friendsList as $friend): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong><?php echo htmlspecialchars($friend['username']); ?></strong>
                            <small class="text-muted">(<?php echo htmlspecialchars($friend['email']); ?>)</small>
                        </div>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="block_user">
                            <input type="hidden" name="user_id" value="<?php echo $friend['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Block</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No friends yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
