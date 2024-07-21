<?php
session_start();
include 'includes/db_config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$conn = connect_db();
$username = $_SESSION['username'];

// Fetch user ID
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['post_content'];
    $image = $_FILES['post_image']['name'];
    $image_temp = $_FILES['post_image']['tmp_name'];
    $upload_dir = './uploads/';

    // Generate a unique name for the image
    $image_new_name = uniqid() . '-' . basename($image);
    $upload_file = $upload_dir . $image_new_name;

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($image_temp, $upload_file)) {
        // Insert the post into the database
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $content, $image_new_name);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Post created successfully!";
        } else {
            $_SESSION['message'] = "Error creating post: " . $stmt->error;
        }
    } else {
        $_SESSION['message'] = "Error uploading image.";
    }

    header('Location: profile.php');
    exit;
}
?>
