<?php
session_start();


$name = $_POST['username'];
$password = $_POST['password'];
$selected_pokemon = $_POST['selected_pokemon'] ?? [];

$host = 'localhost';
$username = 'root';
$password = 'password';
$database = 'test';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT * FROM users2 where username = ? AND password = ? AND pokemons = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $password, $selected_pokemon);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['username'] = $name;
    $_SESSION['logged_in'] = true; 
    header("Location: home.php");
}
else {
    echo "Invalid username or password or pokemons";
    exit;
}

$stmt->close();
$conn->close();

?>