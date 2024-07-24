<?php
include 'common.php';  // Include the common functions and configuration

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$receiver_id = isset($data['receiver_id']) ? intval($data['receiver_id']) : null;
$message = isset($data['message']) ? trim($data['message']) : '';

if (!$receiver_id || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$messageObject = new Message();

// Verify if the user is allowed to send messages to the receiver
if (!$messageObject->isAllowedToMessage($user_id, $receiver_id)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
    exit;
}

$response = $messageObject->sendMessage($user_id, $receiver_id, $message);

echo json_encode($response);
?>
