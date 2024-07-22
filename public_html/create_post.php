<?php
session_start();
include '../config/db_config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Retrieve the user ID from the database using the session username
$username = $_SESSION['username'];
$conn = connect_db();
$query = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caption = $_POST['caption'];

    // Handle image upload
    if (!empty($_FILES['postImage']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["postImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["postImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['message'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["postImage"]["size"] > 500000) {
            $_SESSION['message'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['message'] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $target_file)) {
                // Insert post into database
                $stmt = $conn->prepare("INSERT INTO posts (user_id, image, caption) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $target_file, $caption);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Post created successfully!";
                } else {
                    $_SESSION['message'] = "Error creating post: " . $stmt->error;
                }
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            }
        }
    }

    header('Location: profile.php');
    exit;
}
?>
