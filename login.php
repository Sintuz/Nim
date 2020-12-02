<?php

session_start();

if (isset($_SESSION['user'])) header('Location: index.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['user'] = htmlspecialchars($_POST['username']);
    $_SESSION['language'] = htmlspecialchars($_POST['language']);
    switch ($_POST['difficulty']) {
        case "simple":
            $_SESSION['difficulty'] = 4;
            break;
        case "medium":
            $_SESSION['difficulty'] = 6;
            break;
        case "hard":
            $_SESSION['difficulty'] = 10;
            break;
    }
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
    <script src="js/script.js"></script>

</head>

<body onselectstart='return false'>

<div class="form-wrapper">
    <form action="login.php" method="post">
        <div class="form-group">
            <label id="label-title" for="input-name"><h3 id="name-text">Scegli un nome</h3></label>
            <input id="input-name" type="text" class="form-control" placeholder="Nome" name="username" required>
        </div>
        <div>
            <h3 id="language-text">Lingua</h3>
            <label class="radio-inline">
                <input type="radio" name="language" value="IT" onchange="changeLang(this)" checked>Italiano
            </label>
            <label class="radio-inline">
                <input type="radio" name="language" value="EN" onchange="changeLang(this)">English
            </label>
        </div>
        <div>
            <label for="difficulty-select"><h3 id="difficulty-text">Difficoltà</h3></label>
            <select id="difficulty-select" class="form-control form-control-sm" name="difficulty">
                <option id="simple-text" value="simple" selected>Semplice</option>
                <option id="medium-text" value="medium">Medio</option>
                <option id="hard-text" value="hard">Difficile</option>
            </select>
        </div>
        <button id="submit-button" type="submit" class="btn btn-success">Gioca</button>
    </form>
</div>

<?php include("footer.php"); ?>
</body>

</html>