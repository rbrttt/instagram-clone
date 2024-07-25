<?php
session_start();
include '../../../config/config.php';
include '../php/function.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Not authenticated";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = $_POST['id'];
    $new_message = $_POST['message'];

    if (updateMessage($message_id, $new_message)) {
        echo "Message updated successfully";
    } else {
        echo "Failed to update message";
    }
} else {
    echo "Invalid request method";
}
?>
