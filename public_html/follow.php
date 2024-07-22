<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Check if necessary data is provided
if (!isset($_POST['followed_id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

include_once '../config/db_config.php';
$conn = connect_db();

$follower_id = $_SESSION['user_id'];
$followed_id = $_POST['followed_id'];
$action = $_POST['action'];

if ($action == 'follow') {
    // Insert the follow relationship into the database
    $stmt = $conn->prepare("INSERT INTO followers (follower_id, followed_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $follower_id, $followed_id);
    $stmt->execute();
    $stmt->close();
} elseif ($action == 'unfollow') {
    // Remove the follow relationship from the database
    $stmt = $conn->prepare("DELETE FROM followers WHERE follower_id = ? AND followed_id = ?");
    $stmt->bind_param("ii", $follower_id, $followed_id);
    $stmt->execute();
    $stmt->close();
}

// Get updated follower count for the followed user
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM followers WHERE followed_id = ?");
$stmt->bind_param("i", $followed_id);
$stmt->execute();
$result = $stmt->get_result();
$follower_count = $result->fetch_assoc()['count'];

// Get updated following count for the followed user (not the logged-in user)
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM followers WHERE follower_id = ?");
$stmt->bind_param("i", $followed_id);
$stmt->execute();
$result = $stmt->get_result();
$following_count = $result->fetch_assoc()['count'];

echo json_encode([
    'success' => true,
    'message' => '',
    'action' => $action,
    'follower_count' => $follower_count,
    'following_count' => $following_count
]);

$conn->close();
?>
