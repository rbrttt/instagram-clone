<?php
session_start(); // Start the session

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Fetch user data from the database
include_once '../config/db_config.php';
$conn = connect_db();

$query = "SELECT username FROM users";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>This is your dashboard.</p>
        <button onclick="window.location.href='profile.php'">View Profile</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
        
        <h2>Other Users:</h2>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li><a href="profile.php?username=<?php echo urlencode($row['username']); ?>"><?php echo htmlspecialchars($row['username']); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
