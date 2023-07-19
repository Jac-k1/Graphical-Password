<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page after logging out
header("Location: login.php");
exit;
?>