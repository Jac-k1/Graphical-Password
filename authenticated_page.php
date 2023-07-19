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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        h1 {
            text-align: center;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
        }

        h2 {
            margin-bottom: 20px;
        }

        p {
            margin: 10px 0;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <h1>Authenticated Page</h1>
    </header>
    <div class="container">
        <?php
        echo "<h2>Welcome!</h2>";
        echo "<p>Only logged-in users can access this.</p>";

        // Add links to other pages
        echo "<p><a href='password_change.php'>Change Password</a></p>";
        echo "<p><a href='reset_images.php'>Reset Pokémon Images</a></p>";
        echo "<p><a href='logout.php'>Logout</a></p>";
        ?>
    </div>
</body>

</html>