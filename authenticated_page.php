<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Display the authenticated content
echo "<h2>Welcome, {$_SESSION['username']}!</h2>";
echo "<p>Only logged-in users can access this.</p>";
?>