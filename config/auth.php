<?php
// auth.php

// Include the database configuration file
include_once '../config/db_config.php';

function register($fullname, $username, $password, $email) {
    $conn = connect_db();

    // Check for duplicate username or email
    $check_query = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
    $check_query = $conn->prepare($check_query);
    $check_query->bind_param("ss", $username, $email);
    $check_query->execute();
    $result = $check_query->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists
    if ($user) { 
        if ($user['username'] === $username) {
            return "Username already exists";
        }
        if ($user['email'] === $email) {
            return "Email already exists";
        }
    } else {
        // Hash user password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user details into database
        $query = "INSERT INTO users (fullname, username, password, email) VALUES (?, ?, ?, ?)";
        $query = $conn->prepare($query);
        $query->bind_param("ssss", $fullname, $username, $hashedPassword, $email);

        if ($query->execute()) {
            return "Registration successful! Login now.";
        } else {
            return "Error: " . $query->error;
        }
    }
}

function login($usernameOrEmail, $password) {
    $conn = connect_db();

    // Prepare SQL Statements or query
    $query = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
    $query = $conn->prepare($query);
    $query->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $query->execute();

    // Fetch the result
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists
    if ($user) { // If user exists
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];  // Use the username from the database
            $_SESSION['user_id'] = $user['id'];  // Store user ID in session for easier reference
            return 'Successfully logged in!';
        } else {
            return "Incorrect password";
        }
    } else {
        return "Username or email does not exist";
    }
}
?>
