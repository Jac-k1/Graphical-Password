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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
        exit();
    }

    // Check if the username and password match
    $query = "SELECT user_id FROM users WHERE username = '$username' AND password = '$password'";
    $result = $mysqli->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_ID = $row['user_id'];

        // Retrieve the batches the user needs to verify
        $query = "SELECT DISTINCT order_number FROM pokemon_order WHERE user_id = $user_ID";
        $result = $mysqli->query($query);

        $batches = [];
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row['order_number'];
        }

        // Redirect to the verification page with the user ID and batches
        $_SESSION['user_id'] = $user_ID;
        $_SESSION['batches'] = $batches;
        header("Location: verify.php");
        exit();
    } else {
        echo "Invalid username or password.";
        exit();
    }
}
?>

<!-- Login form -->

<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>


<body>
    <div class="main">
        <form method="post">
            <h1>Login to <section> Poki-Lock</section></h1>
            <p>Enter your Username:</p>
            <input type="text" name="username" placeholder="Enter your username" required>
            <p>Enter your Password:</p>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>