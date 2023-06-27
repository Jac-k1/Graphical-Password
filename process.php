<?php

session_start();


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if selected_pokemon field is present in the form data
    if (isset($_POST['selected_pokemon'])) {
        // Retrieve the selected Pokémon from the form data
        $pokemons = $_POST['selected_pokemon'];
        $name = $_POST['username'];
        $password = $_POST['password'];


        // Perform any validation or processing on the selected Pokémon data
        // For example, you can store them in a database, perform additional checks, etc.

        // Example: Store the selected Pokémon in a database
        $host = 'localhost';
        $database = 'test';
        $username = 'root';
        $password = 'password';

        // Connect to the database
        $connection = mysqli_connect($host, $username, $password, $database);
        if (!$connection) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        // Escape and insert each selected Pokémon into the database
        foreach ($selectedPokemon as $pokemon) {
            $escapedPokemon = mysqli_real_escape_string($connection, $pokemon);
            $query = "INSERT INTO users2 (username, password, pokemons) VALUES (?, ?, ?)";
            mysqli_query($connection, $query);
            $stmt = $conn->prepare($sql);
            if(!$stmt) {
                echo "Prepare failed: (". $conn->errno.") ".$conn->error."<br>";
            }
            $stmt->bind_param("sss", $name, $password, $pokemons);
            $result = $stmt->execute();
        }

        // Close the database connection
        mysqli_close($connection);

        // Display a success message or redirect the user to another page
        echo "Selected Pokémon have been stored in the database.";
    } else {
        // Handle the case where no Pokémon are selected
        echo "No Pokémon selected.";
    }
} else {
    // Handle the case where the form is not submitted
    echo "Form not submitted.";
}
?>