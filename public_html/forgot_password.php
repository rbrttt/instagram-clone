<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config/db_config.php';
    $conn = connect_db();
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $query = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $query->bind_param("ss", $token, $email);
        $query->execute();

        $resetLink = "http://localhost/Comp3340_project/chatApp/php/reset_password.php?token=" . $token;
        $message = "Click on this link to reset your password: " . $resetLink;

        $success = @mail($email, "Password Reset", $message, "From: no-reply@osujic.myweb.cs.uwindsor.ca");

        $successMessages = "Password reset email sent. Please check your email and spam folder.";
    } else {
        $errorMessages = "Email not found.";
    }

    $query->close();
    $conn->close();
}
$pageTitle = 'Forgot Password'; 
include 'header.php';  
?>
<div class="signup-container">
    <div class="signup-form">
        <h2>Chatapp</h2>
        <p>Enter your email to reset your password</p>
        <form id="forgotPasswordForm" action="forgot_password.php" method="post"> <!--form is processed up above-->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" aria-describedby="emailHelp" required>
            </div>
            <div class="input-group">
                <button type="submit">Reset Password</button>
            </div>
            <div class="input-group">
                <p>Remember your password? <a href="../index.php">Login</a></p>
            </div>
            <?php if (isset($errorMessages)): ?>
                <div class="forgot-password-error-message">
                    <?php echo $errorMessages; ?>
                </div>
            <?php elseif (isset($successMessages)): ?>
                <div class="forgot-password-success-message">
                    <?php echo $successMessages; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
        const emailInput = document.getElementById('email');
        const errorMessage = document.getElementById('error-message');
        
        if (!emailInput.validity.valid) {
            event.preventDefault();
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
        }
    });
</script>

<?php include 'footer.php'; ?>
