<?php
session_start();
//page to change user's password

$hostname = 'localhost';
$username = 'sbuytendorp1';
$password = 'sbuytendorp1';
$database = 'sbuytendorp1';

// Create a new MySQLi instance
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check if the connection was successful
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the submitted username and new password
    $username = $_SESSION['username'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Check if the new password and confirm new password match
    if ($newPassword !== $confirmNewPassword) {
        echo "<script>alert('Passwords do not match. Try again.');</script>";
        echo "<script>window.location.href = 'password_change.php';</script>";
        exit();
    }

    // Update the password in the database
    $query = "UPDATE users SET password = '$newPassword' WHERE username = '$username'";
    if ($mysqli->query($query)) {
        echo "Password changed successfully.";
    } else {
        echo "Failed to change password: " . $mysqli->error;
    }
}
?>

<!-- Password Change Form -->
<form method="post">
    <h2>Password Change</h2>
    <input type="password" name="new_password" placeholder="Enter new password" required>
    <input type="password" name="confirm_new_password" placeholder="Confirm new password" required>
    <input type="submit" value="Change Password">
</form>