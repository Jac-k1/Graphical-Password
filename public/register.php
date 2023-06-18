<?php 

session_start();

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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM TEST2USERS where username = $name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username already exists";
        exit;
    }

    $sql = "INSERT INTO TEST2USERS (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt) {
        echo "Prepare failed: (". $conn->errno.") ".$conn->error."<br>";
    }
    $stmt->bind_param("ss", $name, $password);
    $result = $stmt->execute();

    if($result) {
        echo "New record created successfully";
    }
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



 $conn->close();
?>