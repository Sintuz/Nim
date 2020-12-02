<?php

session_start();

if (isset($_SESSION['user'])) header('Location: index.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['user'] = htmlspecialchars($_POST['username']);
    header('Location: index.php');
}
?>


<!DOCTYPE HTML>
<html lang="en">
<head>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Nim</title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>

</head>

<body>

<div class="form-wrapper">
    <form action="login.php" method="post">
        <div class="form-group">
            <label id="label-title" for="input-name"><h2>Choose a username</h2></label>
            <input id="input-name" type="text" class="form-control" placeholder="Username" name="username" required>
        </div>
        <button id="submit-button" type="submit" class="btn btn-success">Play</button>
    </form>
</div>

</body>

</html>