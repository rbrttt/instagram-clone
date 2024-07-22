<?php 
$pageTitle = 'Login'; 
include 'header.php';

session_start();  // Start the session at the beginning

include_once '../config/auth.php';

// Display the success message if it's set
if (isset($_SESSION['message'])) {
    echo "<div class='success-message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);  // Unset the message after displaying it
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = login($_POST['username'], $_POST['password']);
    $_SESSION['message'] = $message;
    if ($message == 'Successfully logged in!') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $_POST['username'];  // Store the username in the session
        header('Location: dashboard.php');  // Redirect to the dashboard page
        exit;
    }
}
?>

<div class="signup-container">
    <div class="signup-form">
        <h2>Chatapp</h2>
        <p>Login to chat and see moments from your friends</p>
        <form id="loginForm" action="index.php" method="post">
            <div class="input-group">
                <label for="username">Username or email</label>
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
            <div class="input-group remember-me">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Remember me</label>
            </div>
            <div class="input-group">
                <button type="submit">Login</button>
            </div>
            <div class="input-group">
                <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
            </div>
            <div class="input-group">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </form>
    </div>
</div>

<?php include '../config/footer.php';?>
