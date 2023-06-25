<?php
// Retrieve the image URL from the query parameter or database
$imageUrl = $_GET['url'];

// Initialize cURL
$ch = curl_init($imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request
$imageData = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Set the appropriate Content-Type header
header('Content-Type: image/jpeg');

// Output the image data
echo $imageData;
?>