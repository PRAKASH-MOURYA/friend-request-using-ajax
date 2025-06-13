$(document).ready(function() {
    // Load friend requests
    function loadFriendRequests() {
        $.ajax({
            url: 'get_friend_requests.php',
            method: 'GET',
            success: function(response) {
                $('#friend-requests-list').html(response);
            }
        });
    }

    // Load posts
    function loadPosts() {
        $.ajax({
            url: 'get_posts.php',
            method: 'GET',
            success: function(response) {
                $('#posts').html(response);
            }
        });
    }

    // Handle friend request actions
    $(document).on('click', '.accept-btn', function() {
        const requestId = $(this).data('request-id');
        $.ajax({
            url: 'handle_friend_request.php',
            method: 'POST',
            data: {
                request_id: requestId,
                action: 'accept'
            },
            success: function(response) {
                loadFriendRequests();
            }
        });
    });

    $(document).on('click', '.reject-btn', function() {
        const requestId = $(this).data('request-id');
        $.ajax({
            url: 'handle_friend_request.php',
            method: 'POST',
            data: {
                request_id: requestId,
                action: 'reject'
            },
            success: function(response) {
                loadFriendRequests();
            }
        });
    });

    // Handle post submission
    $('#post-form').on('submit', function(e) {
        e.preventDefault();
        const content = $(this).find('textarea[name="content"]').val();
        
        $.ajax({
            url: 'create_post.php',
            method: 'POST',
            data: {
                content: content
            },
            success: function(response) {
                $('#post-form textarea').val('');
                loadPosts();
            }
        });
    });

    // Initial load
    loadFriendRequests();
    loadPosts();

    // Refresh friend requests every 30 seconds
    setInterval(loadFriendRequests, 30000);
}); 