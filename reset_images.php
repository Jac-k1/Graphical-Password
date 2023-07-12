<?php
//script that resets the user's pokemon in the database
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
if (!isset($_SESSION['username'])) {
    echo "User not logged in.";
    exit();
}

// Get the user ID based on the stored username
$username = $_SESSION['username'];
$query = "SELECT user_id FROM users WHERE username = '$username'";
$result = $mysqli->query($query);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $user_ID = $row['user_id'];

    // Delete the user's current Pokémon selection from the database
    $query = "DELETE FROM pokemon_order WHERE user_id = $user_ID";
    if (!$mysqli->query($query)) {
        echo "Failed to reset Pokémon selection: " . $mysqli->error;
        exit();
    }

    // Redirect to the select_pokemon.php page
    header("Location: select_pokemon.php");
    exit();
} else {
    echo "User not found.";
    exit();
}
?>