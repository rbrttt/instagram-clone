<?php
include 'common.php';  // Include the common functions and configuration

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Create a User instance
$user = new User();

// Fetch all users from the database
$users = $user->getAllUsers();

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
            <?php foreach ($users as $user): ?>
                <li><a href="profile.php?username=<?php echo urlencode($user['username']); ?>"><?php echo htmlspecialchars($user['username']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
