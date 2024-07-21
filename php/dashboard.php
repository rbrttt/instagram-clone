<?php 
session_start();  // Start the session at the beginning of your script

if (isset($_SESSION['message'])) {
    echo "<div class='success-message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);  // Unset the message
}

echo " Welcome to Dashboard";

?>