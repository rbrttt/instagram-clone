<?php
include 'common.php';  // Include the common functions and configuration
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

$user = new User();

$follower_id = $_SESSION['user_id'];
$followed_id = $_POST['followed_id'];
$action = $_POST['action'];

if ($action == 'follow') {
    $response = $user->followUser($follower_id, $followed_id);
} elseif ($action == 'unfollow') {
    $response = $user->unfollowUser($follower_id, $followed_id);
}

// Get updated follower count for the followed user
$follower_count = $user->getFollowerCount($followed_id);

// Get updated following count for the followed user (not the logged-in user)
$following_count = $user->getFollowingCount($followed_id);

echo json_encode([
    'success' => $response['success'],
    'message' => $response['message'],
    'action' => $action,
    'follower_count' => $follower_count,
    'following_count' => $following_count
]);
?>
