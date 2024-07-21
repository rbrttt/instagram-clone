<?php
$pageTitle = 'Contact Us';
include '../Includes/header.php'; 
?>
<div class="signup-container">
    <div class="signup-form">
        <h2>Contact Us</h2>
        <p>Got a suggestion? Let us know! We'll get back to you shortly.</p>

        <form id="contactForm" action="contact_us.php" method="post">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="message">Your Message</label>
                <textarea id="textarea" name="message" rows="4" cols="25" placeholder="Enter your message here..."></textarea>
            </div>
            <div class="input-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php include '../Includes/footer.php';?>
