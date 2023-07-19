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

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['batches'])) {
    header("Location: login.php");
    exit();
}

$user_ID = $_SESSION['user_id'];
$batches = $_SESSION['batches'];

// Check if all batches have been verified
if (count($batches) === 1) {
    // Verify the selected Pokémon against the database
    $errors = verifyPokemonOrder($mysqli, $_SESSION['verification_results']);
    if (count($errors) === 1) {
        echo "Errors occurred during verification. Please try again.";
    } else {
        echo "All batches have been successfully verified.";
        header("Location: authenticated_page.php");

    }
    unset($_SESSION['verification_results']);
    exit();
}

$batch_number = $batches[0];

// Retrieve the Pokémon data for the current batch
$url = "https://pokeapi.co/api/v2/pokemon?limit=50";
$pokemon_data = json_decode(file_get_contents($url), true)['results'];
?>

<head>
    <title>Pokémon verification</title>
    <link rel="stylesheet" href="register.css">
</head>

<!-- Display the Pokémon sprites for verification -->
<form method="post">
    <div class="selection">
        <p>Please select 1 Pokémon:</p>
        <?php
        foreach ($pokemon_data as $pokemon) {
            $pokemon_id = extractPokemonIdFromUrl($pokemon['url']);
            $pokemon_name = $pokemon['name'];
            $pokemon_sprite = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/$pokemon_id.png";
            ?>
            <label>
                <input type="radio" name="selected_pokemon" value="<?php echo $pokemon_id; ?>">
                <img src="image-proxy.php?url=<?php echo urlencode($pokemon_sprite); ?>" alt="<?php echo $pokemon_name; ?>">
            </label>
            <?php
        }
        ?>
        <br><br>
    </div>
    <input type="submit" value="Verify">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the selected Pokémon ID
    $selected_pokemon_id = $_POST['selected_pokemon'];

    if (empty($selected_pokemon_id)) {
        echo "Please select a Pokémon.";
        exit();
    }

    // Store the verification result in the session array
    $_SESSION['verification_results'][$batch_number] = $selected_pokemon_id;

    // Remove the verified batch from the list
    array_shift($batches);
    $_SESSION['batches'] = $batches;

    if (count($batches) === 0) {
        // Verify the selected Pokémon against the database
        $errors = verifyPokemonOrder($mysqli, $_SESSION['verification_results']);
        if (count($errors) === 0) {
            echo "All Pokemon have been successfully verified.";
        } else {
            echo "Errors occurred during verification. Please try again.";
        }
        unset($_SESSION['verification_results']);
        exit();
    } else {
        // Redirect to the same page to verify the next batch
        header("Location: verify.php");
        exit();
    }
}

function extractPokemonIdFromUrl($url)
{
    $parts = explode('/', rtrim($url, '/'));
    return end($parts);
}

function verifyPokemonOrder($mysqli, $verification_results)
{
    $errors = [];

    $user_ID = $_SESSION['user_id'];

    foreach ($verification_results as $batch_number => $selected_pokemon_id) {
        $query = "SELECT pokemon_id FROM pokemon_order WHERE user_id = $user_ID AND order_number = $batch_number";
        $result = $mysqli->query($query);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $pokemon_order = $row['pokemon_id'];

            if ($selected_pokemon_id != $pokemon_order) {
                $errors[] = "Verification failed";
            }
        } else {
            $errors[] = "Failed to retrieve Pokémon.";
        }
    }

    return $errors;
}
?>