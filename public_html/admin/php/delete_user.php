<?php
include '../../../config/config.php';
include '../php/function.php';

if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    
    // Call the function to delete user
    if (deleteUserByAdmin($userId)) {
        echo "User deleted successfully.";
    } else {
        echo "Failed to delete user.";
    }
} else {
    echo "Invalid request.";
}
?>