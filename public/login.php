<?php
session_start();


//$name = $_POST['username'];
//$password = $_POST['password'];

/*
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
*/

var_dump(function_exists('mysqli_connect'));


$conn = mysqli_connect("localhost", "jpham24", "jpham24", "jpham24");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else {
    echo "Connected successfully\n";
    }

    phpinfo();
?>