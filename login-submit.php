<?php
session_start();


$name = $_POST['username'];
$password = $_POST['password'];


$conn = new mysqli("localhost", "root", "password", "test");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT * FROM users2 where username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['username'] = $name;
    $_SESSION['password'] = $password;
    $_SESSION['logged_in'] = true; 
    header("Location: home.html");
}
else {
    echo "Invalid username or password";
    exit;
}

$stmt->close();
$conn->close();

?>