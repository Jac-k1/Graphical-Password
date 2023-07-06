# Graphical-Password project for SWE
# This is a secondary, graphical password app to help create a more secure login with image selection pulled from an API of over 1000 images
# images pulled from pokeapi.co

#create these tables in phpmyadmin to hold the data

CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255),
  password VARCHAR(255)
);

CREATE TABLE pokemon_sprites (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  sprite_url VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id)
);


--------------------------------------------------------

CREATE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE pokemon_order (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    pokemon_id INT,
    order_number INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

