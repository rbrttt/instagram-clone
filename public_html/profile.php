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
        <div class="profile-header">
            <div class="profile-picture">
                <img src="images/default-profile-picture.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h1>Username</h1>
                <p>Bio: A short description about the user.</p>
                <button class="edit-profile-btn">Edit Profile</button>
            </div>
        </div>
        <div class="profile-stats">
            <div class="stat">
                <span class="number">100</span>
                <span class="label">Posts</span>
            </div>
            <div class="stat">
                <span class="number">200</span>
                <span class="label">Followers</span>
            </div>
            <div class="stat">
                <span class="number">150</span>
                <span class="label">Following</span>
            </div>
        </div>
        <div class="profile-posts">
            <!-- Posts will be dynamically inserted here -->
        </div>
    </div>

    <script src="profile.js"></script>
</body>
</html>
