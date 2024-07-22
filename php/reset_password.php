<?php
include '../config/db_config.php';
$pageTitle = 'Reset Password'; 
include '../config/header.php';  
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Check if the token exists in the database
    $query = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
    $query->bind_param("s", $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $query->bind_param("ss", $hashedPassword, $token);
        $query->execute();

        echo "<div style='text-align: center; background-color: #90EE90; padding: 10px; margin: 20px;'>Password reset successful</div>";
    } else {
        // Token is invalid or expired
        echo "<div style='text-align: center; background-color: #FF6347; padding: 10px; margin: 20px;'>Invalid or expired token</div>";
    }

    $query->close();
    $conn->close();
} else {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    } else {
        echo "<div style='text-align: center; background-color: #FF6347; padding: 10px; margin: 20px;'>Invalid request</div>";
        exit;
    }
}
?>

<div class="signup-container">
    <div class="signup-form">
        <h2>Chatapp</h2>
        <p>Enter your new password</p>
        <form id="resetPasswordForm" action="reset_password.php" method="post"> <!--form is processed up above-->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="input-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit">Reset Password</button>
            </div>
        </form>
    </div>
</div>
