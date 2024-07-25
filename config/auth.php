<?php
// auth.php

// Include the database configuration file
include_once '../config/config.php';

function register($fullname, $username, $password, $email) {
    global $CFG; // Ensure the global configuration is accessible
    $conn = connect_db();

    // Check for duplicate username or email
    $check_query = "SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1";
    $stmt = $conn->prepare($check_query);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user['username'] === $username) {
            return "Username already exists";
        }
        if ($user['email'] === $email) {
            return "Email already exists";
        }
    } else {
        // Hash user password with site-wide salt for security
        $hashedPassword = password_hash($password . $CFG->site_wide_password_salt, PASSWORD_DEFAULT);

        // Insert user details into the database
        $query = "INSERT INTO users (fullname, username, password, email) VALUES (:fullname, :username, :password, :email)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':fullname', $fullname);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':email', $email);

        if ($stmt->execute()) {
            return "Registration successful! Login now.";
        } else {
            return "Registration failed. Please try again.";
        }
    }
}

function login($usernameOrEmail, $password) {
    global $CFG; // Ensure the global configuration is accessible
    $conn = connect_db();

    // Prepare SQL Statements or query
    $query = "SELECT * FROM users WHERE username = :usernameOrEmail OR email = :usernameOrEmail LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':usernameOrEmail', $usernameOrEmail);
    $stmt->execute();

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($user) {
        // Verify the password
        if (password_verify($password . $CFG->site_wide_password_salt, $user['password']) || password_verify($password, $user['password'])) {
            // Check account status
            if ($user['ac_status'] == 1) { // Active
                // Rehash the password with the site-wide salt if needed
                if (!password_verify($password . $CFG->site_wide_password_salt, $user['password'])) {
                    $new_hashed_password = password_hash($password . $CFG->site_wide_password_salt, PASSWORD_DEFAULT);
                    $update_query = "UPDATE users SET password = :new_password WHERE id = :id";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bindValue(':new_password', $new_hashed_password);
                    $update_stmt->bindValue(':id', $user['id']);
                    $update_stmt->execute();
                }

                // Password is correct, start a new session
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];  // Use the username from the database
                $_SESSION['user_id'] = $user['id'];  // Store user ID in session for easier reference
                return 'Successfully logged in!';
            } else { // Blocked
                return 'Your account has been blocked by an admin. Please contact support.';
            }
        } else {
            return 'Incorrect password';
        }
    } else {
        return 'Username or email does not exist';
    }
}
?>
