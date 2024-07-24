<?php
include 'common.php';  // Include the common functions and configuration

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Create User and Post instances
$user = new User();
$post = new Post();
$imageUpload = new ImageUpload();

// Retrieve the user ID from the session
$username = $_SESSION['username'];
$userData = $user->getUserDataByUsername($username);
$user_id = $userData['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caption = sanitize_input($_POST['caption']);

    // Handle image upload
    if (!empty($_FILES['postImage']['name'])) {
        $image = $imageUpload->handleProfilePictureUpload($_FILES['postImage']);
        if (strpos($image, "Sorry") === false) {
            // If the upload was successful, create the post
            $message = $post->createPost($user_id, $caption, $image);
        } else {
            // If there was an error with the upload, set the error message
            $_SESSION['message'] = $image;
            header('Location: profile.php');
            exit;
        }
    } else {
        $_SESSION['message'] = "No image uploaded.";
        header('Location: profile.php');
        exit;
    }

    $_SESSION['message'] = $message;
    header('Location: profile.php');
    exit;
}
?>
