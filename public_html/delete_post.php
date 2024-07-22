<?php
session_start();
include 'includes/db_config.php';

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

// Fetch the post from the database to ensure it belongs to the logged-in user
$conn = connect_db();
$query = "SELECT * FROM posts WHERE id = ? AND user_id = (SELECT id FROM users WHERE username = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $post_id, $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Post not found or doesn't belong to the user
    $_SESSION['message'] = "Error: Post not found or you do not have permission to delete this post.";
    header('Location: profile.php');
    exit;
}

// Delete the post
$query = "DELETE FROM posts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Post deleted successfully!";
} else {
    $_SESSION['message'] = "Error deleting post: " . $stmt->error;
}

header('Location: profile.php');
exit;
?>
