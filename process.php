<?php

session_start();


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if selected_pokemon field is present in the form data
    if (isset($_POST['selected_pokemon'])) {
        // Retrieve the selected Pokémon from the form data
        $selected_pokemon = $_POST['selected_pokemon'] ?? [];
        $name = $_POST['username'] ?? '';
        $passworduploaded = $_POST['password'] ?? '';


        // Perform any validation or processing on the selected Pokémon data
        // For example, you can store them in a database, perform additional checks, etc.

        // Example: Store the selected Pokémon in a database
        $host = 'localhost';
        $database = 'test';
        $username = 'root';
        $password = 'password';

        /*
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users2 (username, password, pokemons) VALUES (?, ?, ?)");
        $pokemonName = implode("", $selected_pokemon);
        $stmt->bind_param("sss", $name, $password, $pokemonName);
        $stmt->execute();
        */



        // Connect to the database
        $connection = new mysqli($host, $username, $password, $database);
        if (!$connection) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $stmt = $connection->prepare("INSERT INTO users2 (username, password, pokemons) VALUES (?, ?, ?)");
        $pokemonName = implode("", $selected_pokemon);
        $stmt->bind_param("sss", $name, $passworduploaded, $pokemonName);
        $stmt->execute();

        $stmt->close();
        // Close the database connection
        mysqli_close($connection);

        // Display a success message or redirect the user to another page
        echo "Account and Pokémon successfully created!";
        echo "<br><br>";
        echo "<a href='login.php'>Login here!</a>";
    } else {
        // Handle the case where no Pokémon are selected
        echo "Missing username, password or Pokémon!";
    }
}
?>