<?php 
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
$pokemonListUrl = 'https://pokeapi.co/api/v2/pokemon?limit=20&offset=' . $offset;
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
echo '<form method="post" action="process.php">';
foreach ($pokemonDetails as $pokemon) {
    $pokemonName = $pokemon->name;
    $pokemonSprite = $pokemon->sprites->front_default;

    echo '<label>';
    echo '<input type="checkbox" name="selected_pokemon[]" value="' . $pokemonSprite . '">';
    echo '<img src="image-proxy.php?url=' . urlencode($pokemonSprite) . '" alt="' . $pokemonName . '">';
    echo '</label>';
}
echo '<br><br>';
echo '<input type="submit" value="Submit">';
echo '</form>';
?>