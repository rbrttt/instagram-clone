<?php
include '../../../config/config.php';
include '../php/function.php';

if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    if (unblockUserByAdmin($userId)) {
        echo "User unblocked successfully.";
    } else {
        echo "Failed to unblock user.";
    }
} else {
    echo "Invalid request.";
}
?>
