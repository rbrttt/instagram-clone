<?php
include_once '../config/db_config.php';

// Fetch user data
$sql = "SELECT * FROM users WHERE id = 1"; // Assuming a user with ID 1 exists for testing
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="container">
        <header class="profile-header">
            <div class="profile-picture">
                <img src="images/default-profile-pic.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h1 class="username"><?php echo htmlspecialchars($user['username']); ?></h1>
                <p class="bio">Bio: <?php echo htmlspecialchars($user['bio']); ?></p>
                <button class="edit-profile-btn" id="editProfileBtn">Edit Profile</button>
            </div>
        </header>
        <div class="profile-stats">
            <div class="stat">
                <span class="number">0</span>
                <span class="label">Posts</span>
            </div>
            <div class="stat">
                <span class="number">0</span>
                <span class="label">Followers</span>
            </div>
            <div class="stat">
                <span class="number">0</span>
                <span class="label">Following</span>
            </div>
        </div>
        <div class="profile-posts">
            <p class="no-posts">No posts yet</p>
        </div>
    </div>

    <!-- Modal for Editing Profile -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h2>Edit Profile</h2>
            <form id="editProfileForm" action="edit-profile.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="profile.js"></script>
</body>
</html>
