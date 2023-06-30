<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='login.css'></link>
    </head>
    <body>
        <div class='main'>
            <h1>Welcome to the Login page</h1>

            <form action='./login-submit.php' method='post'>
                <p>Username</p>
                <input type='text' name="username"></input>
                <br></br>
                <p>Password</p>
                <input type='password' name="password"></input>
                <br></br>


                <?php
            function fetchData($url)
            {
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($curl);
                curl_close($curl);
                return $data;
            }
            // Retrieve a list of 20 random Pokémon from PokeAPI
            $url = 'https://pokeapi.co/api/v2/pokemon?limit=20';
            $pokemonData = json_decode(file_get_contents($url), true)['results'];


            foreach ($pokemonData as $pokemon) {
                $pokemonDetails = json_decode(file_get_contents($pokemon['url']), true);
                $pokemonName = $pokemon['name'];
                $pokemonSprite = $pokemonDetails['sprites']['front_default'];

            // Display the Pokémon sprites in boxes
                echo '<form method="post" action="./login-submit.php">';
                echo '<label>';
                echo '<input type="checkbox" name="selected_pokemon[]" value="' . $pokemonName . '">';
                echo '<img src="' . $pokemonSprite . '" alt="'. $pokemonName . '">';
                echo '</label>';
            }
            echo '<br></br>';
            ?>
                <br></br>
                <br></br>
                <input type='submit' value='Login'></input>
            </form>
        </div>
    </body>
</html>
