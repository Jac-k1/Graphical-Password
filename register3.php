<?php
session_start();

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

// Retrieve the current batch number or initialize it as 0
$current_batch = isset($_GET['batch']) ? $_GET['batch'] : 1;

if ($current_batch > 3) {
    // Redirect to login.php if all batches are completed
    header("Location: login.php");
    exit();
}

if ($current_batch === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the submitted username
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        echo "Please enter a username.";
        exit();
    }

    // Store the username in a session variable
    $_SESSION['username'] = $username;

    // Insert the username into the database if it doesn't exist
    $query = "SELECT user_id FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows === 0) {
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (!$mysqli->query($query)) {
            echo "Registration failed: " . $mysqli->error;
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the selected Pokemon ID
    $selected_pokemon_id = $_POST['selected_pokemon'];

    if (empty($selected_pokemon_id)) {
        echo "Please select a Pokémon.";
        exit();
    }

    // Get the user ID based on the stored username
    $username = $_SESSION['username'];
    $query = "SELECT user_id FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_ID = $row['user_id'];

        // Insert the selected Pokémon ID into the database
        $escaped_pokemonID = $mysqli->real_escape_string($selected_pokemon_id);
        $query = "INSERT INTO pokemon_order (user_id, pokemon_id, order_number) VALUES ($user_ID, $escaped_pokemonID, $current_batch)";
        $mysqli->query($query);

        // Redirect to the same page to load the next batch of Pokémon
        $next_batch = $current_batch + 1;
        header("Location: register3.php?batch=$next_batch");
        exit();
    } else {
        echo "User not found.";
        exit();
    }
}

// Calculate the offset for retrieving the Pokémon data
$offset = ($current_batch - 1) * 10;

// Retrieve the Pokémon data for the current batch
$url = "https://pokeapi.co/api/v2/pokemon?limit=10&offset=$offset";
$pokemon_data = json_decode(file_get_contents($url), true)['results'];
?>

<!-- Add a JavaScript function for form validation -->
<script>
    function validateForm() {
        var selectedPokemon = document.forms["registrationForm"]["selected_pokemon"].value;

        if (selectedPokemon === "") {
            alert("Please select a Pokémon.");
            return false;
        }
    }
</script>

<!-- Display the Pokémon sprites in boxes -->
<form name="registrationForm" method="post" onsubmit="return validateForm();">
    <?php if ($current_batch === 1) { ?>
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <p>Please select 1 Pokemon: </p>
    <?php } else { ?>
        <p>Please select 1 Pokemon: </p>
        <input type="hidden" name="username" value="<?php echo $_SESSION['username'] ?? ''; ?>">
        <input type="hidden" name="password" value="<?php echo $_SESSION['password'] ?? ''; ?>">
    <?php } ?>
    <input type="hidden" name="current_batch" value="<?php echo $current_batch; ?>">
    <div id="pokemon_selection">
        <?php 
        foreach ($pokemon_data as $pokemon) {
            $pokemon_id = extractPokemonIdFromUrl($pokemon['url']);
            $pokemon_name = $pokemon['name'];
            $pokemon_sprite = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/$pokemon_id.png";
        ?>
        <label>
            <input type="radio" name="selected_pokemon" value="<?php echo $pokemon_id; ?>">
            <img src="<?php echo $pokemon_sprite; ?>" alt="<?php echo $pokemon_name; ?>">
        </label>
        <?php 
        }
        ?>
    </div>
    <br><br>
    <input type="submit" value="Submit">
</form>

<?php
function extractPokemonIdFromUrl($url)
{
    $parts = explode('/', rtrim($url, '/'));
    return end($parts);
}
?>
