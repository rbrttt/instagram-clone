<?php
session_start(); // Start the session

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

// Retrieve the username from the query parameter or session
$username = isset($_GET['username']) ? $_GET['username'] : $_SESSION['username'];

// Fetch user data from the database
include_once '../config/db_config.php';
$conn = connect_db();

$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user data was found
if ($user) {
    $bio = $user['bio'];
    $profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'images/default-profile-pic.jpg';
} else {
    // Handle the case where user data is not found
    $_SESSION['message'] = "User not found.";
    header("location: dashboard.php");
    exit;
}

// Fetch user posts from the database
$query = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

// Get the number of posts
$post_count = count($posts);

// Fetch follower and following counts for the profile being viewed
$query = "SELECT COUNT(*) as count FROM followers WHERE followed_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$follower_count = $result->fetch_assoc()['count'];

$query = "SELECT COUNT(*) as count FROM followers WHERE follower_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$following_count = $result->fetch_assoc()['count'];

// Check if the logged-in user is following this profile
$isFollowing = false;
if ($username !== $_SESSION['username']) {
    $loggedInUserId = $_SESSION['user_id'];
    $query = "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?";
    $stmt->prepare($query);
    $stmt->bind_param("ii", $loggedInUserId, $user['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $isFollowing = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <div class="nav-buttons">
        <a href="dashboard.php">&lt; Dashboard</a>
        <a href="#" id="directMessagesBtn"><i class="fas fa-comments"></i> Direct Messages</a>
    </div>
    <div class="container">
        <header class="profile-header">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h1 class="username"><?php echo htmlspecialchars($user['username']); ?></h1>
                <p class="bio">Bio: <?php echo htmlspecialchars($user['bio']); ?></p>
                <?php if ($username === $_SESSION['username']): // Show Edit Profile button only for the logged-in user ?>
                    <button class="edit-profile-btn" id="editProfileBtn">Edit Profile</button>
                <?php else: // Show Follow/Unfollow button for other users ?>
                    <form id="followForm" method="POST">
                        <input type="hidden" name="followed_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="action" value="<?php echo $isFollowing ? 'unfollow' : 'follow'; ?>" class="action-btn">
                            <?php echo $isFollowing ? 'Unfollow' : 'Follow'; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </header>
        <div class="profile-stats">
            <div class="stat">
                <span class="number"><?php echo $post_count; ?></span>
                <span class="label">Posts</span>
            </div>
            <div class="stat">
                <span class="number"><?php echo $follower_count; ?></span>
                <span class="label">Followers</span>
            </div>
            <div class="stat">
                <span class="number"><?php echo $following_count; ?></span>
                <span class="label">Following</span>
            </div>
        </div>
        <hr>
        <div class="profile-posts">
            <?php if (empty($posts)): ?>
                <p class="no-posts">No posts yet</p>
            <?php else: ?>
                <div class="post-grid">
                    <?php foreach ($posts as $post): ?>
                        <div class="post">
                            <div class="post-image-container">
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                                <?php if ($username === $_SESSION['username']): // Show Delete button only for the logged-in user ?>
                                    <form action="delete_post.php" method="POST" class="delete-post-form" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                        <button type="submit" class="delete-post-btn"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p><?php echo htmlspecialchars($post['caption']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($username === $_SESSION['username']): // Show Create Post button only for the logged-in user ?>
                <button class="action-btn" id="createPostBtn">Create Post</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for Editing Profile -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h2>Edit Profile</h2>
            <form id="editProfileForm" action="edit_profile.php" method="POST" enctype="multipart/form-data">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>

                <label for="profile_pic">Profile Picture:</label>
                <input type="file" id="profile_pic" name="profile_pic">
                
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Modal for Creating Post -->
    <div id="createPostModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closePostModal">&times;</span>
            <h2>Create Post</h2>
            <form id="createPostForm" action="create_post.php" method="POST" enctype="multipart/form-data">
                <label for="postImage">Image:</label>
                <input type="file" id="postImage" name="postImage" accept="image/*" required>
                
                <label for="caption">Caption:</label>
                <textarea id="caption" name="caption"></textarea>
                
                <button type="submit">Post</button>
            </form>
        </div>
    </div>

    <script src="js/profile.js"></script>
</body>
</html>
