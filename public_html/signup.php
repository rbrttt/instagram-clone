<?php

session_start(); // Start session
$pageTitle = 'Signup'; 
include 'header.php';  
include_once '../config/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = register($_POST['fullname'], $_POST['username'], $_POST['password'], $_POST['email']);
    $_SESSION['message'] = $message;
    if ($message == 'Registration successful! Login now.') {
        header('Location: index.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-form">
            <h2>Chatapp</h2>
            <p>Sign up to chat and see moments from your friends</p>
            <form id="signupForm" action="signup.php" method="post">
                <div class="input-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                        <span>Show</span>
                    </span>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group remember-me">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Remember me</label>
                </div>
                <div class="input-group">
                    <button type="submit">Sign Up</button>
                </div>
                <div class="input-group">
                    <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                </div>
                <div class="input-group">
                    <p>Already have an account? <a href="index.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
<script src="js/signup.js"></script>
</body>
</html>
