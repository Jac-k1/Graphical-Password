<!DOCTYPE html>
<html>

<head>
    <title>Registration Form</title>
    <style>
        .pokemon-sprite {
            width: 100px;
            height: 100px;
        }
    </style>
    <script>
        function validateForm() {
            var username = document.forms["registrationForm"]["username"].value;
            var password = document.forms["registrationForm"]["password"].value;
            var selectedPokemonOrder = document.getElementById("selected_pokemon_order").value;

            if (username === "" || password === "") {
                alert("Please fill in all fields.");
                return false;
            }

            if (selectedPokemonOrder === "") {
                alert("Please select at least one Pokémon.");
                return false;
            }
        }

        function updateSelectedPokemonOrder(checkbox) {
            var selectedPokemonOrder = document.getElementById("selected_pokemon_order").value;

            if (checkbox.checked) {
                selectedPokemonOrder += checkbox.value + ",";
            } else {
                selectedPokemonOrder = selectedPokemonOrder.replace(checkbox.value + ",", "");
            }

            document.getElementById("selected_pokemon_order").value = selectedPokemonOrder;
        }
    </script>
</head>

<body>
    <h1>Registration Form</h1>
    <form name="registrationForm" method="post" action="register.php" onsubmit="return validateForm();">
        <label>Username: <input type="text" name="username"></label><br>
        <label>Password: <input type="password" name="password"></label><br>
        <input type="hidden" name="selected_pokemon_order" id="selected_pokemon_order" value="">
        <h2>Select Pokémon:</h2>
        <?php
        $url = 'https://pokeapi.co/api/v2/pokemon?limit=30';
        $pokemon_data = json_decode(file_get_contents($url), true)['results'];

        foreach ($pokemon_data as $index => $pokemon) {
            $pokemon_name = $pokemon['name'];
            $pokemon_sprite = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/" . getPokemonIdFromUrl($pokemon['url']) . ".png";
            ?>
            <label>
                <input type="checkbox" name="selected_pokemon[]" value="<?php echo $pokemon_name; ?>"
                    onclick="updateSelectedPokemonOrder(this)">
                <img class="pokemon-sprite" src="<?php echo $pokemon_sprite; ?>" alt="<?php echo $pokemon_name; ?>">
            </label>
        <?php } ?>

        <br><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>

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
function getPokemonIdFromUrl($url)
{
    $parts = explode('/', rtrim($url, '/'));
    return end($parts);
}

// Retrieve the submitted username and password
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insert the username/password combination into the users table
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($mysqli->query($query)) {
        // Get the ID of the newly inserted user
        $userID = $mysqli->insert_id;

        // Retrieve the selected Pokémon names and their order
        $selectedPokemonOrder = $_POST['selected_pokemon_order'];
        $selectedPokemon = explode(",", $selectedPokemonOrder);

        // Store the selected Pokémon in the desired order
        foreach ($selectedPokemon as $order => $pokemonName) {
            // Escape the Pokémon name, user ID, and order to prevent SQL injection
            $escapedPokemonName = $mysqli->real_escape_string($pokemonName);
            $escapedOrder = $mysqli->real_escape_string($order + 1);

            // Insert the Pokémon name, user ID, and order into the pokemon_order table
            $query = "INSERT INTO pokemon_order2 (user_id, pokemon_name, selection_order) VALUES ($userID, '$escapedPokemonName', '$escapedOrder')";
            $mysqli->query($query);
        }

        // Redirect to the login page or perform additional actions
        header("Location: login.php");
        exit();
    } else {
        // If the query fails, display an error message
        echo "Registration failed: " . $mysqli->error;
    }
}
?>