<?php
session_start();
include 'includes/db_config.php'; // Adjust the path if necessary

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
    $bio = $_POST['bio'];

    // Handle profile picture upload if a file was uploaded
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['message'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["profile_pic"]["size"] > 500000) {
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
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $profile_pic = $target_file;

                // Update the user's profile picture and bio
                $stmt = $conn->prepare("UPDATE users SET profile_pic=?, bio=? WHERE id=?");
                $stmt->bind_param("ssi", $profile_pic, $bio, $user_id);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Profile updated successfully!";
                } else {
                    $_SESSION['message'] = "Error updating profile: " . $stmt->error;
                }
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // If no profile picture was uploaded, update only the bio
        $stmt = $conn->prepare("UPDATE users SET bio=? WHERE id=?");
        $stmt->bind_param("si", $bio, $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Profile updated successfully!";
        } else {
            $_SESSION['message'] = "Error updating profile: " . $stmt->error;
        }
    }

    header('Location: profile.php');
    exit;
}
?>
