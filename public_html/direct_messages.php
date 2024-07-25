<?php
include 'common.php';  // Include the common functions and configuration

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$userObject = new User();

$followers = $userObject->getFollowers($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Direct Messages</title>
    <link rel="stylesheet" href="css/direct_messages.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <script>
        const currentUserId = <?php echo json_encode($user_id); ?>;
    </script>
</head>
<body>
    <div id="main">
        <button class="openbtn" onclick="openNav()">☰</button>
        <div class="sidebar" id="mySidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <a href="profile.php">View Profile</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="direct_messages.php">Direct Messages</a>
            <div class="divider"></div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        
        <div class="messages-container">
            <div class="users-list">
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                </div>
                <ul>
                    <?php foreach ($followers as $row): ?>
                        <li data-user-id="<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['profile_pic']); ?>" alt="Profile Picture">
                            <span><?php echo htmlspecialchars($row['username']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="chat-box">
                <div class="chat-header">
                    <h3>Select a user to start chatting</h3>
                </div>
                <div class="chat-content">
                    <!-- Chat messages will be displayed here -->
                </div>
                <div class="chat-input">
                    <input type="text" placeholder="Type a message" id="chatInput">
                    <button id="sendMessageBtn">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/direct_messages.js"></script>
    <script src="js/sidebar.js"></script>
</body>
</html>
