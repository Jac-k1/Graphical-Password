<?php

session_start();


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if selected_pokemon field is present in the form data
    if (isset($_POST['selected_pokemon'])) {
        // Retrieve the selected Pokémon from the form data
        $selected_pokemon = $_POST['selected_pokemon'];
        $name = $_POST['username'];
        $password = $_POST['password'];


        // Perform any validation or processing on the selected Pokémon data
        // For example, you can store them in a database, perform additional checks, etc.

        // Example: Store the selected Pokémon in a database
        $host = 'localhost';
        $database = 'sbuytendorp1';
        $username = 'sbuytendorp1';
        $password = 'sbuytendorp1';

        // Connect to the database
        $connection = new mysqli($host, $username, $password, $database);
        if (!$connection) {
            die('Database connection failed: ' . mysqli_connect_error());
        }

        $stmt = $connection->prepare("INSERT INTO users2 (username, password, pokemons) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $password, $pokemons);
        $pokemons = implode(',', $selected_pokemon);
        $stmt->execute();
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