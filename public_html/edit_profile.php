<?php
include 'common.php';  // Include the common functions and configuration

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Create User and ImageUpload instances
$user = new User();
$imageUpload = new ImageUpload();

// Retrieve the user ID from the session
$username = $_SESSION['username'];
$userData = $user->getUserDataByUsername($username);
$user_id = $userData['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bio = sanitize_input($_POST['bio']);

    // Handle profile picture upload if a file was uploaded
    if (!empty($_FILES['profile_pic']['name'])) {
        $profile_pic = $imageUpload->handleProfilePictureUpload($_FILES['profile_pic']);
        if (strpos($profile_pic, "Sorry") === false) {
            // If the upload was successful, update the profile with the new picture
            $message = $user->updateProfile($user_id, $bio, $profile_pic);
        } else {
            // If there was an error with the upload, set the error message
            $_SESSION['message'] = $profile_pic;
            header('Location: profile.php');
            exit;
        }
    } else {
        // If no profile picture was uploaded, update only the bio
        $message = $user->updateProfile($user_id, $bio);
    }

    $_SESSION['message'] = $message;
    header('Location: profile.php');
    exit;
}
?>
