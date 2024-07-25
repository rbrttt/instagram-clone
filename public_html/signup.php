<?php

include 'common.php';  // Include the common functions and configuration
$pageTitle = 'Signup';
$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $fullname = sanitize_input($_POST['fullname']);
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $email = sanitize_input($_POST['email']);
    
    // Validate inputs
    if (empty($fullname) || empty($username) || empty($password) || empty($email)) {
        $_SESSION['message'] = 'All fields are required.';
        $_SESSION['form_data'] = $_POST;
        header('Location: signup.php');
        exit;
    }
    
    // Register user
    $message = $user->register($fullname, $username, $password, $email);
    $_SESSION['message'] = $message;
    if ($message == 'Registration successful! Login now.') {
        header('Location: index.php');
    } else {
        $_SESSION['form_data'] = $_POST;
        header('Location: signup.php');
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="error-message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <div class="signup-container">
        <div class="signup-form">
            <h2>Chatapp</h2>
            <p>Sign up to chat and see moments from your friends</p>
            <form id="signupForm" action="signup.php" method="post">
                <div class="input-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" autocomplete="name" value="<?php echo isset($_SESSION['form_data']['fullname']) ? htmlspecialchars($_SESSION['form_data']['fullname']) : ''; ?>" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" autocomplete="username" value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                        <span>Show</span>
                    </span>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" autocomplete="email" value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
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

<?php include 'footer.php'; ?>
<script src="js/signup.js"></script>
</body>
</html>

<?php unset($_SESSION['form_data']); ?>
