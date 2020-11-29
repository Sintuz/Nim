<?php

session_start();

if (isset($_SESSION['user'])) header('Location: index.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['user'] = htmlspecialchars($_POST['username']);
    $_SESSION['difficulty'] = $_POST['difficulty'];
    var_dump($_POST);
    header('Location: index.php');
}
?>


<!DOCTYPE HTML>
<html lang="it">
<head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Nim</title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
            integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
            crossorigin="anonymous"></script>

</head>

<body>

<div class="form-wrapper">
    <form action="login.php" method="post">
        <div class="form-group">
            <label id="label-title" for="input-name"><h2>Choose a username</h2></label>
            <input id="input-name" type="text" class="form-control" placeholder="Username" name="username" required>
        </div>
        <h4>Choose a level of difficulty</h4>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="difficulty" id="inlineRadioSimple"
                   value="simple" checked>
            <label class="form-check-label" for="inlineRadioSimple">Simple</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="difficulty" id="inlineRadioHard" value="hard">
            <label class="form-check-label" for="inlineRadioHard">Hard</label>
        </div>
        <br>
        <button id="submit-button" type="submit" class="btn btn-success">Play</button>
    </form>
</div>

</body>

</html>