<?php 
$hostname = 'localhost';
$username = 'root';
$password = 'password';
$database = 'test';

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

// Retrieve a list of 30 Pokémon from PokeAPI
$pokemonListUrl = 'https://pokeapi.co/api/v2/pokemon?limit=30';
$pokemonListData = fetchData($pokemonListUrl);
$pokemonList = json_decode($pokemonListData)->results;

// Retrieve the details and default sprites for each Pokémon
$pokemonDetails = [];
foreach ($pokemonList as $pokemon) {
    $pokemonUrl = $pokemon->url;
    $pokemonData = fetchData($pokemonUrl);
    $pokemonDetails[] = json_decode($pokemonData);
}
?>

<!-- Add a JavaScript function for form validation -->
<script>
function validateForm() {
    var username = document.forms["registrationForm"]["username"].value;
    var password = document.forms["registrationForm"]["password"].value;
    var selectedPokemon = document.querySelectorAll('input[name="selected_pokemon[]"]:checked');

    if (username === "" || password === "") {
        alert("Please fill in all fields.");
        return false;
    }

    if (selectedPokemon.length === 0) {
        alert("Please select at least one Pokémon.");
        return false;
    }
}
</script>

<!-- Display the Pokémon sprites in boxes -->
<form name="registrationForm" method="post" onsubmit="return validateForm();">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <?php foreach ($pokemonDetails as $pokemon) {
        $pokemonSprite = $pokemon->sprites->front_default;
        $pokemonName = $pokemon->name;
        ?>
        <label>
            <input type="checkbox" name="selected_pokemon[]" value="<?php echo $pokemonSprite; ?>">
            <img src="image-proxy.php?url=<?php echo urlencode($pokemonSprite); ?>" alt="<?php echo $pokemonName; ?>">
        </label>
    <?php } ?>
    <br><br>
    <input type="submit" value="Submit">
</form>

<?php
// Retrieve the submitted username and password
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insert the username/password combination into the database
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($mysqli->query($query)) {
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

        // Redirect to login.php
        header("Location: login.php");
        exit();
    } else {
        // If the query fails, display an error message
        echo "Registration failed: " . $mysqli->error;
    }
}
?>