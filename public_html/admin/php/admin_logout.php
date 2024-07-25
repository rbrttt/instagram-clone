<?php
session_start();
session_destroy(); // Destroy all data registered to a session
header("Location: ../admin_index.html"); // Redirect to the login page
exit;
?>