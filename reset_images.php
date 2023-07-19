<?php
session_start();

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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_ID = $_SESSION['user_id'];

// Delete the user's current Pokémon selection from the database
$query = "DELETE FROM pokemon_order WHERE user_id = $user_ID";
if (!$mysqli->query($query)) {
    echo "Failed to reset Pokémon selection: " . $mysqli->error;
    exit();
}

// Redirect to the select_pokemon.php page
header("Location: select_pokemon.php");
exit();
?>