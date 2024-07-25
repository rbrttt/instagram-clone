<?php
include 'common.php';  // Include the common functions and configuration

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$contact_id = isset($data['contact_id']) ? intval($data['contact_id']) : null;

if (!$contact_id) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$messageObject = new Message();

// Verify if the user is allowed to view messages with the contact
if (!$messageObject->isAllowedToMessage($user_id, $contact_id)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
    exit;
}

$messages = $messageObject->getMessages($user_id, $contact_id);

echo json_encode(['success' => true, 'messages' => $messages]);
?>
