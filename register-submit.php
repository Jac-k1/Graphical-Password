<?php 

session_start();

$name = $_POST['username'];
$password = $_POST['password'];

$host = "localhost";
$user = "root";
$pass = "password";
$dbname = "test";

//create conn
$conn = new mysqli($host, $user, $pass, $dbname);
    //check conn
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT * FROM users2 where username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username already exists";
        exit;
    }

    //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users2 (username, password, pokemons) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt) {
        echo "Prepare failed: (". $conn->errno.") ".$conn->error."<br>";
    }
    $stmt->bind_param("sss", $name, $password, $pokemons);
    $result = $stmt->execute();

    if($result) {
        echo "New record created successfully";
        echo "<a href='login.html'>Login here</a>";
    }
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    

$stmt->close();
$conn->close();
?>
