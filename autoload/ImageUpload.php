<?php

class ImageUpload {
    public function handleProfilePictureUpload($file) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Debugging: Check the target file path
        error_log("Target file: " . $target_file);

        // Check if image file is a actual image or fake image
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            error_log("File is not an image.");
            return "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($file["size"] > 500000) {
            error_log("File is too large.");
            return "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            error_log("Invalid file format.");
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            error_log("File was not uploaded.");
            return "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                error_log("File uploaded successfully: " . $target_file);
                return $target_file;
            } else {
                error_log("Error uploading file.");
                return "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>
