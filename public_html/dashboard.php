<?php
session_start(); // Start the session

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: index.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <p>This is your dashboard.</p>
    <button onclick="window.location.href='profile.php'">View Profile</button>
    <button onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>
