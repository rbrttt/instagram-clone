<?php
include 'common.php';  // Include the common functions and configuration

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Check if the post ID is set
if (!isset($_POST['post_id'])) {
    header('Location: profile.php');
    exit;
}

$post_id = $_POST['post_id'];

// Create instances of User and Post
$user = new User();
$post = new Post();

// Retrieve the user ID from the session
$username = $_SESSION['username'];
$userData = $user->getUserDataByUsername($username);
$user_id = $userData['id'];

// Fetch the post to ensure it belongs to the logged-in user
$postData = $post->getPostById($post_id);

if ($postData && $postData['user_id'] == $user_id) {
    // Delete the post
    $message = $post->deletePost($post_id);
    $_SESSION['message'] = $message;
} else {
    // Post not found or doesn't belong to the user
    $_SESSION['message'] = "Error: Post not found or you do not have permission to delete this post.";
}

header('Location: profile.php');
exit;
?>
