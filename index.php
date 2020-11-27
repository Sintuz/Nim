<?php
session_start();

if (!isset($_SESSION['user'])) header('Location: login.php');

$table = array(
    array(),
    array(),
    array(),
    array(),
);

if (isset($_SESSION['table'])) {
    $table = unserialize($_SESSION['table']);
} /*else {
    for ($i = 0; $i < 4; $i++) {
        $max = rand(0, 6);
        for ($j = 6; $j > $max; $j--) {
            $table[$i][$j] = 0;
        }
        for ($j = $max; $j >= 0; $j--) {
            $table[$i][$j] = 1;
        }
    }
    $_SESSION['table'] = serialize($table);
}*/

?>

<!DOCTYPE HTML>
<html lang="it">
<head>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Nim</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/script.js"></script>

</head>

<body>
<?php var_dump(($_SESSION['log'])) ?>
<p id="title">Nim</p>
<p>Welcome <?php echo $_SESSION['user'] ?></p>
<p><a href="logout.php">Logout</a></p>
<p>Wins: <?php echo $_SESSION['wins'] ?></p>
<p>Losses: <?php echo $_SESSION['losses'] ?></p>
<table id="game">
    <tr>
        <td>
            <table id="board">
                <?php

                echo "<tbody>";
                for ($i = 7; $i >= 1; $i--) {
                    echo "<tr>";
                    echo sprintf("<td><img src='https://dummyimage.com/30x20/ffffff/000&text=%d' alt='block'></td>", $i);
                    for ($j = 0; $j < 4; $j++) {
                        if ($table[$j][$i - 1] == 1)
                            echo sprintf("<td id='%d %d' onclick='play(this.id)'><img src='https://dummyimage.com/30x20/000000/fff&text=%d%d' alt='block'></td>", $j, $i - 1, $j, $i - 1);
                        else
                            echo "<td></td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";


                echo "<tfoot>";
                echo "<tr>";
                echo "<td></td>";
                for ($i = 0; $i < 4; $i++) {
                    echo sprintf("<td><img src='https://dummyimage.com/30x20/ffffff/000&text=%d' alt='block'></td>", $i + 1);
                }
                echo "</tr>";
                echo "</tfoot>";


                ?>
            </table>
        </td>
        <td>
            hello
        </td>
    </tr>
</table>

</body>
</html>