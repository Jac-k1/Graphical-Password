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

if ($current_batch > 4) {
    // Redirect to login.php if all batches are completed
    header("Location: login.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the selected Pokemon ID
    $selected_pokemon_id = $_POST['selected_pokemon'];

    if (empty($selected_pokemon_id)) {
        echo "Please select a Pokémon. Make sure to remember the order.";
        exit();
    }

    $user_ID = $_SESSION['user_id'];

    // Insert the selected Pokémon ID into the database
    $escaped_pokemonID = $mysqli->real_escape_string($selected_pokemon_id);
    $query = "INSERT INTO pokemon_order (user_id, pokemon_id, order_number) VALUES ($user_ID, $escaped_pokemonID, $current_batch)";
    if ($mysqli->query($query)) {
        // Redirect to the next batch or logout if completed
        if ($current_batch === 4) {
            header("Location: select_pokemon.php?batch=5"); // Go to the final batch (5) which triggers the alert.
            exit();
        } else {
            header("Location: select_pokemon.php?batch=" . ($current_batch + 1));
            exit();
        }
    } else {
        echo "Failed to insert Pokémon selection: " . $mysqli->error;
        exit();
    }
}

// Retrieve the Pokémon data for the current batch
$url = "https://pokeapi.co/api/v2/pokemon?limit=50";
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

<head>
    <title>Pokémon Selection</title>
    <link rel="stylesheet" href="register.css">
</head>

<!-- Display the Pokémon sprites in boxes -->
<form name="registrationForm" method="post" onsubmit="return validateForm();">
    <div class="selection">
        <p>Please select 1 Pokémon: </p>
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
                    <img src="image-proxy.php?url=<?php echo urlencode($pokemon_sprite); ?>"
                        alt="<?php echo $pokemon_name; ?>">
                </label>
                <?php
            }
            ?>
        </div>
        <br><br>
    </div>
    <input type="submit" value="Submit">
</form>

<?php
function extractPokemonIdFromUrl($url)
{
    $parts = explode('/', rtrim($url, '/'));
    return end($parts);
}
?>
