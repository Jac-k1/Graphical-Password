<?php
session_start();


//$name = $_POST['username'];
//$password = $_POST['password'];

/*
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test_database";

$conn = new mysqli($host, $user, $pass, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
else {
    echo "Connected successfully\n";
    }

$conn->close();
*/


$conn = mysqli_connect("localhost", "root", "", "test_database");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else {
    echo "Connected successfully\n";
    }
?>