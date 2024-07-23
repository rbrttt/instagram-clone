<?php
session_start();

include_once '../config/db_config.php';

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

$conn = connect_db();

// Verify if the user is allowed to view messages with the contact
$query = "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $contact_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
    exit;
}

$query = "SELECT messages.message, messages.sender_id, users.username AS sender_name, users.profile_pic AS sender_profile_pic
          FROM messages
          JOIN users ON messages.sender_id = users.id
          WHERE (messages.sender_id = ? AND messages.receiver_id = ?)
             OR (messages.sender_id = ? AND messages.receiver_id = ?)
          ORDER BY messages.created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $user_id, $contact_id, $contact_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $row['sender_profile_pic'] = htmlspecialchars($row['sender_profile_pic']); // Ensure path is safe for output
    $messages[] = $row;
}

echo json_encode(['success' => true, 'messages' => $messages]);
?>
