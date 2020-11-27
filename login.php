<?php

session_start();

if (isset($_SESSION['user'])) header('Location: index.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $server = 'localhost';
    $db_username = 'sintuz';
    $db_password = 'Spintacy#1';
    $db_name = 'nim';

    $conn = new mysqli($server, $db_username, $db_password, $db_name);
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']);

    $query = "SELECT  id, username, wins, losses, board FROM `users` WHERE username=\"" . $username . "\" AND password=\"" . $password . "\";";

    $result = $conn->query($query);
    if (!$result) {
        $message = 'Invalid query: ' . $conn->error . "\n";
        $message .= 'Whole query: ' . $query;
        die($message);
    }

    $res = $result->fetch_all();

    $len = sizeof($res);

    if ($len == 1) {
        $_SESSION['user'] = $res[0][1];
        $_SESSION['wins'] = $res[0][2];
        $_SESSION['losses'] = $res[0][3];
        $_SESSION['table'] = $res[0][4];
        header('Location: index.php');
    } else {
        echo "Username o password not correct";
    }
}
?>


<!DOCTYPE HTML>
<html lang="it">
<head>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Nim</title>

</head>

<body>

<p id="title">Login</p>
<form method="POST" action="login.php">
    <label>Username
        <input type="text" placeholder="username" name="username">
    </label>
    <br>
    <label>Password
        <input type="password" placeholder="password" name="password">
    </label>
    <br>
    <button type="submit">Submit</button>
</form>

</body>
</html>