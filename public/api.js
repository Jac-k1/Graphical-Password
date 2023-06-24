let pokemon;
const total_pokemons = 500;

window.onload = function () {
  function randomize(min, max) {
    // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
  }

  function showPokemon() {
    pokemon = document.getElementById("show");

    for (let i = 0; i < 20; i++) {
      pokemon.innerHTML = "";
      fetchData(randomize(1, total_pokemons));
    }
  }

  function fetchData(id) {
    fetch(`https://pokeapi.co/api/v2/pokemon/${id}`)
      .then((response) => response.json())
      .then(function (data) {
        let sprites = data.sprites.other.dream_world.front_default;
        if (sprites) {
          printPokemon(sprites);
        }
      });
  }

  function printPokemon(sprites) {
    let template = `<div class="card" style="width: 8rem;">
  <img src="${sprites}" class="card-img-top" alt="..." id="sprite"></img>
 </div>
  `;
    pokemon.innerHTML += template;
  }
  showPokemon();
};
