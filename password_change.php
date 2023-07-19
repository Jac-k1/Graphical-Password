<?php
session_start();

// Check if the user is logged in and verification is complete
if (!isset($_SESSION['user_id']) || !isset($_SESSION['verification_complete']) || $_SESSION['verification_complete'] !== true) {
    header("Location: logout.php"); // Redirect to logout.php if verification is not complete
    exit();
}

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

$user_ID = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the submitted password
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('New password and confirm password do not match.'); window.location.href = 'password_change.php';</script>";
        exit();
    }

    // Check if the current password is correct
    $query = "SELECT password FROM users WHERE user_id = $user_ID";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the current password
        if ($currentPassword !== $storedPassword) {
            echo "<script>alert('Incorrect current password.'); window.location.href = 'password_change.php';</script>";
            exit();
        }

        // Update the password in the database
        $newPassword = $mysqli->real_escape_string($newPassword);
        $query = "UPDATE users SET password = '$newPassword' WHERE user_id = $user_ID";

        if ($mysqli->query($query)) {
            echo "<script>alert('Password updated successfully. You will be logged out now.'); window.location.href = 'logout.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to update password: " . $mysqli->error . "'); window.location.href = 'password_change.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href = 'password_change.php';</script>";
        exit();
    }
}
?>

<head>
    <title>Password Change</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <div class="main">
        <form method="post">
            <h1>Change Password</h1>
            <p>Current Password:</p>
            <input type="password" name="current_password" placeholder="Enter current password" required>
            <p>New Password:</p>
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <p>Confirm New Password:</p>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <br><br>
            <input type="submit" value="Change Password">
        </form>
    </div>
</body>