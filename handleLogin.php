<?php
session_start();

// Hard-coded credentials for demonstration
require_once 'config/secret.inc';

// Check if we have POST data
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify credentials
    if ($username === CORRECT_USERNAME && $password === CORRECT_PASSWORD) {
        // Credentials are correct
        $_SESSION['user'] = $username; // Store user in session
        header('Location: '.$_POST["next"]??"edit.php"); // Redirect to a welcome page or dashboard
    } else {
        // Credentials are incorrect
        $error = 'Invalid username or password.';
        header('Location: login.php?error=' . urlencode($error)); // Redirect back to login with error
    }
    exit();
} else {
    // No POST data
    header('Location: index.php');
    exit();
}