<?php
// Make it easy to turn on/off debugging site-wide. 0 is off.
define('SITE_DEBUG', 0);

// Automatically load config.php.
require_once '../config/config.php';

// Ensure that session_start() is always called...
ini_set('session.gc_maxlifetime', $CFG->session_timeout);
ini_set('session.cookie_lifetime', 0); // i.e., cookies are not permanent
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Custom autoload function
function my_autoloader($class) {
    $file = CLASS_DIR . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// Add support for PHP autoloading...
define('CLASS_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);
spl_autoload_register('my_autoloader');

// Sanitize input
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Debugging function (if you want to keep it for future use)
function debug($message) {
    if (SITE_DEBUG) {
        error_log($message);
    }
}
