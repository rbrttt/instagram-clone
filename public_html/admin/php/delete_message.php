<?php
include '../../../config/config.php';
include '../php/function.php';

if (isset($_POST['id'])) {
    $messageId = $_POST['id'];
    $success = deleteMessage($messageId); // Call the function from function.php

    if ($success) {
        echo "Message deleted successfully.";
    } else {
        echo "Failed to delete message.";
    }
} else {
    echo "Invalid request.";
}
?>
