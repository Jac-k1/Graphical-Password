<?php
session_start();


$name = $_POST['username'];
$password = $_POST['password'];


$host = "localhost";
$user = "jpham24";
$pass = "jpham24";
$dbname = "jpham24";

$conn = new mysqli($host, $user, $pass, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "Connected successfully\n";
    }

$conn->close();
?>