<?php

session_start();

if (isset($_SESSION['user'])) header('Location: index.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['user'] = htmlspecialchars($_POST['username']);
    header('Location: index.php');
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
    <label>Choose a name
        <input type="text" placeholder="username" name="username" required>
    </label>
    <br>
    <button type="submit">Submit</button>
</form>

</body>
</html>