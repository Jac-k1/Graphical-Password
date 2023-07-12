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

function fetchData($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the username and password
    if (empty($username) || empty($password)) {
        echo '<script>alert("Username and password are required.");</script>';
    } else {
        // Check if the username and password match a record in the database
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $mysqli->query($query);

        if ($result->num_rows === 1) {
            // Username and password are correct

            // Retrieve the selected Pokémon for the user from the database
            $row = $result->fetch_assoc();
            $userID = $row['id'];

            // Retrieve the selected Pokémon from the form
            $selectedPokemon = $_POST['selected_pokemon'];

            // Retrieve the Pokémon sprites stored in the database for the user
            $query = "SELECT sprite_url FROM pokemon_sprites WHERE user_id = $userID";
            $result = $mysqli->query($query);
            $storedPokemon = [];

            while ($row = $result->fetch_assoc()) {
                $storedPokemon[] = $row['sprite_url'];
            }

            // Check if the selected Pokémon matches the stored Pokémon
            if (count($selectedPokemon) === count($storedPokemon) && array_diff($selectedPokemon, $storedPokemon) === []) {
                // Selected Pokémon match the stored Pokémon

                // Store the username in the session
                $_SESSION['username'] = $username;

                // Redirect to authenticated_page.php
                header("Location: authenticated_page.php");
                exit;
            } else {
                // Selected Pokémon do not match the stored Pokémon
                echo '<script>alert("Incorrect Pokémon selection.");</script>';
            }
        } else {
            // Invalid username or password
            echo '<script>alert("Invalid username or password.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
        function validateForm() {
            var username = document.forms["loginForm"]["username"].value;
            var password = document.forms["loginForm"]["password"].value;
            var selectedPokemon = document.querySelectorAll('input[name="selected_pokemon[]"]:checked');

            if (username === '' || password === '') {
                alert('Username and password are required.');
                return false;
            }

            if (selectedPokemon.length < 4) {
                alert('Please select at least 4 Pokémon.');
                return false;
            }
        }
    </script>
</head>
<body>
    <h2>Login</h2>
    <form name="loginForm" method="post" onsubmit="return validateForm()">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>

        <!-- Display the Pokémon sprites in checkboxes for selection -->
        <?php
        $pokemonListUrl = 'https://pokeapi.co/api/v2/pokemon?limit=30';
        $pokemonListData = fetchData($pokemonListUrl);
        $pokemonList = json_decode($pokemonListData)->results;

        $pokemonDetails = [];
        foreach ($pokemonList as $pokemon) {
            $pokemonUrl = $pokemon->url;
            $pokemonData = fetchData($pokemonUrl);
            $pokemonDetails[] = json_decode($pokemonData);
        }

        foreach ($pokemonDetails as $pokemon) {
            $pokemonSprite = $pokemon->sprites->front_default;
            $pokemonName = $pokemon->name;

            echo '<label>';
            echo '<input type="checkbox" name="selected_pokemon[]" value="' . $pokemonSprite . '">';
            echo '<img src="image-proxy.php?url=' . urlencode($pokemonSprite) . '" alt="' . $pokemonName . '">';
            echo '</label>';
        }
        ?>

        <input type="submit" value="Login">
    </form>
</body>
</html>