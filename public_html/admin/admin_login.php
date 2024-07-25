<?php
session_start();
include '../../config/config.php';
include 'php/function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_data = [
        'username_or_email' => $_POST['username_or_email'],
        'password' => $_POST['password']
    ];
    $login_result = checkAdminUser($login_data);

    if ($login_result['status']) {
        $_SESSION['admin_id'] = $login_result['user_id'];
        header("Location: php/admin_dashboard.php");
    } else {
        echo "Invalid credentials";
    }
}
?>
