<?php
$NUM_COLUMN = 4;
define("NUM_COLUMN_BIN", 3);
define("NUM_ROW", 7);
session_start();
if (!isset($_SESSION['difficulty'])) {
    header('Location: login.php');
    exit(0);
}

function check_win($board)
{
    global $NUM_COLUMN;
    $flag = true;
    for ($i = 0; $i < $NUM_COLUMN; $i++) {
        for ($j = 0; $j < NUM_ROW; $j++) {
            if ($board[$i][$j] == 1) $flag = false;
        }
    }
    return $flag;
}

function get_counts($board)
{
    global $NUM_COLUMN;
    $counts = array(0, 0, 0, 0);
    for ($i = 0; $i < $NUM_COLUMN; $i++) {
        for ($j = 0; $j < NUM_ROW; $j++) {
            if ($board[$i][$j] == 1) {
                $counts[$i]++;
            }
        }
    }
    return $counts;
}

function convert_to_bin($counts)
{
    global $NUM_COLUMN;
    $counts_bin = array();
    for ($i = 0; $i < $NUM_COLUMN; $i++) {
        $counts_bin[$i] = sprintf("%03d", decbin($counts[$i]));
    }
    return $counts_bin;
}

function sum_binary_pos($counts_bin)
{
    global $NUM_COLUMN;
    $sums = array(0, 0, 0);
    for ($i = 0; $i < NUM_COLUMN_BIN; $i++) {
        for ($j = 0; $j < $NUM_COLUMN; $j++) {
            $sums[$i] += $counts_bin[$j][$i];
        }
    }
    return $sums;
}

function get_odd_sums($sums)
{
    $pos = array();
    $j = 0;
    for ($i = 0; $i < NUM_COLUMN_BIN; $i++) {
        if ($sums[$i] % 2 != 0) {
            $pos[$j++] = $i;
        }
    }
    if (count($pos) == 0) {
        for ($i = 0, $j = 0; $i < NUM_COLUMN_BIN; $i++) {
            if ($sums[$i] != 0) {
                $pos[$j++] = $i;
            }
        }
    }
    return $pos;
}

function find_col_with_power_in_pos($counts_bin, $power)
{
    global $NUM_COLUMN;
    $col = -1;

    for ($i = 0; $i < $NUM_COLUMN; $i++) {
        if ($counts_bin[$i][$power] == 1) {
            $col = $i;
        }
    }

    return $col;
}

function remove_from_column($board, $col, $num)
{
    // taking out the cells
    $i = NUM_ROW - 1;
    while ($board[$col][$i] == 0) {
        $i--;
    }
    $temp = $num;
    for (; $temp--; $i--) {
        $board[$col][$i] = 0;
    }
    return $board;
}

function new_board()
{
    global $NUM_COLUMN;
    $board = array(
        array(),
        array(),
        array(),
        array(),
    );
    for ($i = 0; $i < $NUM_COLUMN; $i++) {
        $max = rand(0, NUM_ROW - 1);

        // declaring empty cells
        for ($j = NUM_ROW - 1; $j > $max; $j--) {
            $board[$i][$j] = 0;
        }

        // filling remaining cells
        for ($j = $max; $j >= 0; $j--) {
            $board[$i][$j] = 1;
        }
    }
    return $board;
}


if (isset($_SESSION['board'])) {
    $board = unserialize($_SESSION['board']);
    $NUM_COLUMN = $_SESSION['difficulty'];
} else {
    $NUM_COLUMN = $_SESSION['difficulty'];
    $flag = true;

    $board = 0;

    while ($flag) {
        $board = new_board();

        // counting the number of active cells in every column
        $counts = get_counts($board);

        // converting the number into binary
        $counts_bin = convert_to_bin($counts);

        // summing the value of every position of the binary values
        $sums = sum_binary_pos($counts_bin);

        $flag1 = false;
        for ($i = 0; $i < NUM_COLUMN_BIN; $i++) {
            if ($sums[$i] % 2 != 0) $flag1 = true;
        }
        if ($flag1 == false) {
            $flag = false;
        }
    }

    $_SESSION['board'] = serialize($board);
}
if (!isset($_SESSION['wins'])) {
    $_SESSION['wins'] = 0;
    $_SESSION['losses'] = 0;
    $_SESSION['action'] = null;
    $_SESSION['winner'] = null;
}

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $board = unserialize($_SESSION['board']);

    $coords = explode(" ", $_POST['id']);

    $x = intval($coords[0]);
    $y = intval($coords[1]);

    if ($board[$x][$y] == 1) {
        for ($i = $y; $i < NUM_ROW; $i++) {
            $board[$x][$i] = 0;
        }

        if (check_win($board)) {
            $_SESSION['winner'] = 'player';
            $_SESSION['wins']++;
        } else {

            // counting the number of active cells in every column
            $counts = get_counts($board);

            // converting the number into binary
            $counts_bin = convert_to_bin($counts);

            // summing the value of every position of the binary values
            $sums = sum_binary_pos($counts_bin);

            // get all odd powers
            $powers = get_odd_sums($sums);

            // determining a valid column
            $col = find_col_with_power_in_pos($counts_bin, $powers[0]);

            // determining the number of cells to take out
            $num = pow(2, 2 - $powers[0]);

            // remove num amount of cell from col column
            $board = remove_from_column($board, $col, $num);
            /*
                        $log = array(
                            'counts' => $counts,
                            'counts_bin' => $counts_bin,
                            'sums' => $sums,
                            'powers' => $powers,
                            'col' => $col,
                            'num' => $num,
                        );
                        $_SESSION['log'] = serialize($log);*/

            if (check_win($board)) {
                $_SESSION['winner'] = 'computer';
                $_SESSION['losses']++;
            }
        }

        $_SESSION['board'] = serialize($board);

        http_response_code(200);
    }
    exit(0);

}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Nim</title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>

</head>

<body onmousedown='return false' onselectstart='return false'>
<div id="container">
    <p id="title">Nim</p>
    <table id="game">
        <tr>
            <td>
                <div id="board">
                    <table>
                        <?php
                        global $NUM_COLUMN;
                        echo "<tbody>";
                        for ($i = NUM_ROW - 1; $i >= 0; $i--) {
                            echo "<tr>";
                            echo sprintf("<td><img src='imgs/%d.png' alt='block'></td>", $i + 1);
                            for ($j = 0; $j < $NUM_COLUMN; $j++) {
                                if ($board[$j][$i] == 1)
                                    echo sprintf("<td id='%d %d' onclick='play(this.id)'><img class='cell' src='imgs/cell.png' alt='block'></td>", $j, $i);
                                else
                                    echo "<td></td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody>";


                        echo "<tfoot>";
                        echo "<tr>";
                        echo "<td></td>";
                        for ($i = 0; $i < $NUM_COLUMN; $i++) {
                            echo sprintf("<td><img src='imgs/%d.png' alt='block'></td>", $i + 1);
                        }
                        echo "</tr>";
                        echo "</tfoot>";

                        ?>
                    </table>
                </div>
            </td>
            <td>
                <div id="info">
                    <p>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                            if ($_SESSION['winner'] == null) {
                                /*
                                $counts = get_counts(unserialize($_SESSION['board']));
                                echo "<table>";
                                echo "<tbody>";
                                echo "<tr>";
                                echo sprintf("<td>%s</td>", $_SESSION['language']=="IT"?"N colonna":"Column");
                                for ($i = 0; $i < $NUM_COLUMN; $i++) {
                                    echo sprintf("<td>%d</td>", $i + 1);
                                }
                                echo "</tr>";
                                echo "<tr>";
                                echo sprintf("<td>%s</td>", $_SESSION['language']=="IT"?"Lunghezza dec":"Length dec");
                                for ($i = 0; $i < $NUM_COLUMN; $i++) {
                                    echo sprintf("<td>%d</td>", $counts[$i]);
                                }
                                echo "</tr>";
                                echo "<tr>";
                                echo sprintf("<td>%s</td>", $_SESSION['language']=="IT"?"Lunghezza bin":"Length bin");
                                for ($i = 0; $i < $NUM_COLUMN; $i++) {
                                    echo sprintf("<td>%s</td>", sprintf("%03d", decbin($counts[$i])));
                                }
                                echo "</tr>";
                                echo "</tbody>";
                                echo "</table>";
                                echo "<hr>";*/
                                if ($_SESSION['language'] == "IT") {
                                    echo "<p>Come giocare:</p>";
                                    echo "<p>Ogni turno clicca su una cella per<br> rimuovere essa e tutte quelle sopra.</p>";
                                    echo "<p>Lo scopo del gioco per vincere<br> é rimuovere l'ultima cella.</p>";
                                } else {
                                    echo "<p>How to play:</p>";
                                    echo "<p>Every turn click on a cell to remove<br> it and all the other above.</p>";
                                    echo "<p>The aim of the game to win is<br> to remove the last cell.</p>";
                                }
                            } else {
                                if ($_SESSION['winner'] == "computer") {
                                    echo sprintf("<p class='text-danger win-loss-text'>%s!</p>", $_SESSION['language'] == "IT" ? "Hai perso" : "You lost");
                                } else {
                                    echo sprintf("<p class='text-danger win-loss-text'>%s!</p>", $_SESSION['language'] == "IT" ? "Hai vinto" : "You won");
                                }
                                echo "<br>";
                                unset($_SESSION['board']);
                                $_SESSION['winner'] = null;
                                echo sprintf("<button class='btn btn-primary' onclick='location.reload();'>%s</button>", $_SESSION['language'] == "IT" ? "Nuova partita" : "New game");
                            }
                        }
                        ?>
                    </p>
                </div>
            </td>
        </tr>
    </table>
    <?php
    if ($_SESSION['language'] == "IT") {
        echo sprintf("<p>Benvenuto %s</p>", $_SESSION['user']);
        echo sprintf("<p>Vittorie: %d<br>Sconfitte: %d</p>", $_SESSION['wins'], $_SESSION['losses']);
        echo sprintf("<a href='logout.php'>Esci</a>");
    } else {
        echo sprintf("<p>Welcome %s</p>", $_SESSION['user']);
        echo sprintf("<p>Wins: %d<br>Losses: %d</p>", $_SESSION['wins'], $_SESSION['losses']);
        echo sprintf("<a href='logout.php'>Logout</a>");
    }
    ?>
</div>

<?php include("footer.php"); ?>
</body>
</html>