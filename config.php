<?php
// Ensure this file is included only once
if (!defined('CONFIG_PHP_INCLUDED')) {
    define('CONFIG_PHP_INCLUDED', true);

    // Start the session only if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Database connection settings
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'event_management';

    $conn = new MySQLi($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Utility functions
    function sanitize_input($data) {
        return htmlspecialchars(trim($data));
    }

    function hash_password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>