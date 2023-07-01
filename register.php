<?php 
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

// Generate a random offset value between 0 and 1261
$offset = rand(0, 1261);

// Retrieve a list of 20 random Pokémon from PokeAPI
$pokemonListUrl = 'https://pokeapi.co/api/v2/pokemon?limit=30&offset=' . $offset;
$pokemonListData = fetchData($pokemonListUrl);
$pokemonList = json_decode($pokemonListData)->results;

// Retrieve the details and default sprites for each Pokémon
$pokemonDetails = [];
foreach ($pokemonList as $pokemon) {
    $pokemonUrl = $pokemon->url;
    $pokemonData = fetchData($pokemonUrl);
    $pokemonDetails[] = json_decode($pokemonData);
}

// Display the Pokémon sprites in boxes
echo '<form method="post">';
echo 'Username: <input type="text" name="username"><br>';
echo 'Password: <input type="password" name="password"><br>';

// Store the order of selected Pokémon sprites in a hidden input field
echo '<input type="hidden" name="sprite_order" value="">';

$i = 0;
foreach ($pokemonDetails as $pokemon) {
    $pokemonSprite = $pokemon->sprites->front_default;
    $pokemonName = $pokemon->name;

    echo '<label>';
    echo '<input type="checkbox" name="selected_pokemon[]" value="' . $pokemonSprite . '">';
    echo '<img src="image-proxy.php?url=' . urlencode($pokemonSprite) . '" alt="' . $pokemonName . '">';
    echo '</label>';

    // Store the index of the Pokémon sprite in the hidden input field
    echo '<input type="hidden" name="sprite_order[' . $i . ']" value="' . $pokemonSprite . '">';

    $i++;
}

echo '<input type="submit" value="Register">';
echo '</form>';


// Retrieve the submitted username and password
$username = $_POST['username'];
$password = $_POST['password'];

// Insert the username/password combination into the database
$query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
$mysqli->query($query);

// Get the ID of the newly inserted user
$userID = $mysqli->insert_id;

// Retrieve the selected Pokémon sprites
$selectedPokemon = $_POST['selected_pokemon'];

// Insert the selected Pokémon sprites into the database
foreach ($selectedPokemon as $pokemonSprite) {
    // Escape the sprite URL and user ID to prevent SQL injection
    $escapedSprite = $mysqli->real_escape_string($pokemonSprite);

    // Insert the sprite URL and user ID into the database
    $query = "INSERT INTO pokemon_sprites (user_id, sprite_url) VALUES ($userID, '$escapedSprite')";
    $mysqli->query($query);
}
?>