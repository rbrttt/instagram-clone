<?php 
include 'common.php';  // Include the common functions and configuration
$pageTitle = 'Login';

require_once '../config/auth.php'; // Include auth functions
// require_once '../autoload/User.php'; // Manually include the User class

// Display the success message if it's set
if (isset($_SESSION['message'])) {
    echo "<div class='success-message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);  // Unset the message after displaying it
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $usernameOrEmail = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    
    // Validate inputs
    if (empty($usernameOrEmail) || empty($password)) {
        $_SESSION['message'] = 'Both username and password are required.';
        $_SESSION['form_data'] = $_POST;
        header('Location: index.php');
        exit;
    }

    // Login user
    $message = login($usernameOrEmail, $password);
    $_SESSION['message'] = $message;
    if ($message == 'Successfully logged in!') {
        $_SESSION['loggedin'] = true;
        $user = new User();
        // Fetch user data using the username stored in the session
        $userData = $user->getUserDataByUsername($_SESSION['username']);
        $_SESSION['user_id'] = $userData['id'];  // Ensure user_id is set
        header('Location: dashboard.php');  // Redirect to the dashboard page
        exit;
    } else {
        $_SESSION['form_data'] = $_POST;
        header('Location: index.php');
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
            <p>Login to chat and see moments from your friends</p>
            <form id="loginForm" action="index.php" method="post">
                <div class="input-group">
                    <label for="username">Username or email</label>
                    <input type="text" id="username" name="username" autocomplete="username" value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
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
                    <a href="admin/admin_index.html" class="forgot-password">Admin Page</a>
                </div>
                <div class="input-group">
                    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>

<?php include 'footer.php'; ?>
<script src="js/signup.js"></script>
</body>
</html>

<?php unset($_SESSION['form_data']); ?>
