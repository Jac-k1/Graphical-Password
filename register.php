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

function fetchData($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
/*
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
*/

$url = 'https://pokeapi.co/api/v2/pokemon?limit=30';
$pokemon_data = json_decode(file_get_contents($url), true)['results'];

?>

<!-- Add a JavaScript function for form validation -->
<script>
    function validateForm() {
        var username = document.forms["registrationForm"]["username"].value;
        var password = document.forms["registrationForm"]["password"].value;
        var selected_pokemon = document.querySelectorAll('input[name="selected_pokemon[]"]:checked');

        if (username === "" || password === "") {
            alert("Please fill in all fields.");
            return false;
        }

        if (selected_pokemon.length === 0) {
            alert("Please select at least one Pokémon.");
            return false;
        }
    }
</script>

<!-- Display the Pokémon sprites in boxes -->
<form name="registrationForm" method="post" onsubmit="return validateForm();">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    <?php 
        foreach ($pokemon_data as $pokemon) {
        $pokemon_detail = json_decode(file_get_contents($pokemon['url']), true);
        $pokemon_name = $pokemon['name'];
        $pokemon_sprite = $pokemon_detail['sprites']['front_default'];
    ?>
    <label>
        <input type="checkbox" name="selected_pokemon[]" value="<?php echo $pokemon_detail['id']; ?>">
        <img src="<?php echo $pokemon_sprite?>" alt="<?php echo $pokemon_name; ?>">
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
        $user_ID = $mysqli->insert_id;

        // Retrieve the selected Pokémon sprites
        $selected_pokemon = $_POST['selected_pokemon'];

        // Insert the selected Pokémon sprites into the database
        $order = 1;
        foreach ($selected_pokemon as $pokemon_ID) {
            // Escape the sprite URL and user ID to prevent SQL injection
            $escaped_pokemonID = $mysqli->real_escape_string($pokemon_ID);

            // Insert the sprite URL and user ID into the database
            $query = "INSERT INTO pokemon_order (user_id, pokemon_id, order_number) VALUES ($user_ID, $escaped_pokemonID, $order)";
            $mysqli->query($query);

            $order++;
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