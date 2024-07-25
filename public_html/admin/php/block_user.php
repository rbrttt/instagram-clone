<?php
include '../../../config/config.php';
include '../php/function.php';

if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    if (blockUserByAdmin($userId)) {
        echo "User blocked successfully.";
    } else {
        echo "Failed to block user.";
    }
} else {
    echo "Invalid request.";
}
?>
