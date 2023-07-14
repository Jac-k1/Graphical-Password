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

$current_batch = isset($_GET['batch']) ? $_GET['batch'] : 1;

if ($current_batch > 4) {
    header("Location: authenticated_page.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_pokemon_id = $_POST['selected_pokemon'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        $escaped_pokemonID = $mysqli->real_escape_string($selected_pokemon_id);
        $order_number = $current_batch;

        // Check if the selected Pokémon ID and order exist in the database
        $query = "SELECT * FROM pokemon_order WHERE user_id = '$user_id' AND pokemon_id = '$escaped_pokemonID' AND order_number = '$order_number'";
        $result = $mysqli->query($query);

        if ($result->num_rows === 0) {
            // The selected Pokémon is correct for the current batch
            $next_batch = $current_batch + 1;
            if ($next_batch > 4) {
                header("Location: authenticated_page.php");
            } else {
                header("Location: login.php?batch=$next_batch");
            }
            exit();
        } else {
            echo '<script>alert("The selected Pokémon is incorrect. Please try again.");</script>';
            exit();
        }
    } else {
        echo '<script>alert("Something is wrong. Check username, password, and/or Pokémon");</script>';
        exit();
    }
}

$url = "https://pokeapi.co/api/v2/pokemon?limit=50";
$pokemon_data = json_decode(file_get_contents($url), true)['results'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
        function validateForm() {
            var selectedPokemon = document.querySelectorAll('input[name="selected_pokemon"]:checked');
            var password = document.forms["loginForm"]["password"].value;
            var username = document.forms["loginForm"]["username"].value;

            if (selectedPokemon.length === 0) {
                alert("Please select a Pokémon.");
                return false;
            }

            if (password === "" || username === "") {
                alert("please enter a username and password.");
                return false;
            }
        }
    </script>
</head>
<body>
    <h2>Login</h2>
    <form name="loginForm" method="post" onsubmit="return validateForm()">
        <?php if ($current_batch === 1) { ?>
            Username: <input type="text" name="username"><br>
            Password: <input type="password" name="password"><br>
            <p>Please select 1 Pokémon: </p>
        <?php } else { ?>
            <p>Please select 1 Pokémon: </p>
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
                    <img src="image-proxy.php?url=<?php echo urlencode($pokemon_sprite); ?>" alt="<?php echo $pokemon_name; ?>">
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
</body>
</html>
