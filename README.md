# Graphical-Password project for SWE

# This is a secondary, graphical password app to help create a more secure login with image selection pulled from an API of over 1000 images

# images pulled from pokeapi.co

#create these tables in phpmyadmin to hold the data

CREATE TABLE users (
user_id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL,
password VARCHAR(255) NOT NULL
);

CREATE TABLE pokemon_order (
order_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
pokemon_id INT NOT NULL,
order_number INT NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(user_id),
UNIQUE KEY unique_order (user_id, order_number)
);

#for non-codd testing, replace line 149 of register.php, line 104 in reset_images.php, line 117 in reset_image.php, and line 59 of verify.php with
<img src="<?php echo $pokemon_sprite?>" alt="<?php echo $pokemon_name; ?>">
