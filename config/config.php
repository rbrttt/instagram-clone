<?php
$CFG = new stdClass();

// Replace the following URL with your site's URL
// $CFG->base_url = 'https://comp3340.preney.myweb.cs.uwindsor.ca/';

// Site-wide password salt (Ensure you set a secure, random salt)
$CFG->site_wide_password_salt = 'e3a1b6c8d74e4fa2a9f5e7c6b5a4f3d2e1b0c9a8b7a6d5c4f3e2d1c0b1a2e3f4';

// Set a "global" session timeout (in seconds)
$CFG->session_timeout = 60 * 15; // 15 minutes

// Database information
$CFG->db_dsn = 'mysql:host=localhost;dbname=instagram_clone';
$CFG->db_user = 'root';
$CFG->db_pass = '';

// Special database "admin" security settings
$CFG->db_admin_permit_create_drop = FALSE;
$CFG->db_admin_only_allow_ip = '70.25.8.171';

// Special email support address
$CFG->emailaddr_support = 'preney@uwindsor.ca';

// Database connection function
function connect_db() {
    global $CFG;
    try {
        $conn = new PDO($CFG->db_dsn, $CFG->db_user, $CFG->db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
