<?php
session_start();

// Check if the user is logged in and verification is complete
if (!isset($_SESSION['user_id']) || !isset($_SESSION['verification_complete']) || $_SESSION['verification_complete'] !== true) {
    header("Location: logout.php"); // Redirect to logout.php if verification is not complete
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authenticated Page</title>
    <link rel="stylesheet" type="text/css" href="authn.css">
</head>

<body>
    <header>
        <h1>Authenticated Page</h1>
    </header>
    <div class="main">
        <?php
        echo "<h2><section>Welcome!</section></h2>";
        echo "<p>Only logged-in users can access this.</p>";

        // Add links to other pages
        echo "<p><a href='password_change.php'>Change Password</a></p>";
        echo "<p><a href='reset_images.php'>Reset Pokémon Images</a></p>";
        echo "<p><a href='logout.php'>Logout</a></p>";
        ?>
    </div>
</body>

</html>