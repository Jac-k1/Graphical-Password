<?php 

$host = "localhost";
$user = "jpham24";
$pass = "jpham24";
$dbname = "jpham24";

//create conn
$conn = new mysqli($host, $user, $pass, $dbname);
    //check conn
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else {
        echo "Connected successfully";
        }

 $conn->close();
?>
