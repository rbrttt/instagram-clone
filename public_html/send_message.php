<?php
session_start();

include_once '../config/db_config.php';

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

$conn = connect_db();

// Verify if the user is allowed to send messages to the receiver
$query = "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $receiver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
    exit;
}

$query = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $user_id, $receiver_id, $message);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
}
?>
