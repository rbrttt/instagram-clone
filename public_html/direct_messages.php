<?php
session_start(); // Start the session

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

include_once '../config/db_config.php';
$conn = connect_db();

$query = "SELECT u.id, u.username, u.profile_pic 
          FROM users u
          JOIN followers f ON u.id = f.followed_id
          WHERE f.follower_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Direct Messages</title>
    <link rel="stylesheet" href="css/direct_messages.css">
    <script>
        const currentUserId = <?php echo json_encode($user_id); ?>;
    </script>
</head>
<body>
    <div class="messages-container">
        <div class="users-list">
            <div class="search-bar">
                <input type="text" placeholder="Search">
            </div>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li data-user-id="<?php echo $row['id']; ?>">
                        <img src="<?php echo htmlspecialchars($row['profile_pic']); ?>" alt="Profile Picture">
                        <span><?php echo htmlspecialchars($row['username']); ?></span>
                    </li>
                <?php endwhile; ?>
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

    <script src="js/direct_messages.js"></script>
</body>
</html>
