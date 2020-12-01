<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: login.php');

function check_win($board)
{
    $flag = true;
    for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 7; $j++) {
            if ($board[$i][$j] == 1) $flag = false;
        }
    }
    return $flag;
}

function get_counts($board)
{
    $counts = array(0, 0, 0, 0);
    for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 7; $j++) {
            if ($board[$i][$j] == 1) {
                $counts[$i]++;
            }
        }
    }
    return $counts;
}

function convert_to_bin($counts)
{
    $counts_bin = array();
    for ($i = 0; $i < 4; $i++) {
        $counts_bin[$i] = sprintf("%03d", decbin($counts[$i]));
    }
    return $counts_bin;
}

function sum_binary_pos($counts_bin)
{
    $sums = array(0, 0, 0);
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $sums[$i] += $counts_bin[$j][$i];
        }
    }
    return $sums;
}

function get_odd_sums($sums)
{
    $pos = array();
    $j = 0;
    for ($i = 0; $i < 3; $i++) {
        if ($sums[$i] % 2 != 0) {
            $pos[$j++] = $i;
        }
    }
    if(count($pos)==0) {
        for ($i = 0, $j=0; $i < 3; $i++) {
            if ($sums[$i] != 0) {
                $pos[$j++] = $i;
            }
        }
    }
    return $pos;
}

function find_col_with_power_in_pos($counts_bin, $power)
{
    $col = -1;

    for ($i = 0; $i < 4; $i++) {
        if ($counts_bin[$i][$power] == 1) {
            $col = $i;
        }
    }

    return $col;
}

function remove_from_column($board, $col, $num)
{
    // taking out the cells
    $i = 6;
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
    $board = array(
        array(),
        array(),
        array(),
        array(),
    );
    for ($i = 0; $i < 4; $i++) {
        $max = rand(0, 6);

        // declaring empty cells
        for ($j = 6; $j > $max; $j--) {
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
} else {
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
        for ($i = 0; $i < 3; $i++) {
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
        for ($i = $y; $i < 7; $i++) {
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
<html lang="it">
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

<body>
<!--<?php var_dump(unserialize($_SESSION['log'])) ?>-->
<p id="title">Nim</p>
<p>Welcome <?php echo $_SESSION['user'] ?></p>
<p><a href="logout.php">Logout</a></p>
<p>Wins: <?php echo $_SESSION['wins'] ?></p>
<p>Losses: <?php echo $_SESSION['losses'] ?></p>
<div id="container">
    <table id="game">
        <tr>
            <td>
                <table id="board">
                    <?php

                    echo "<tbody>";
                    for ($i = 6; $i >= 0; $i--) {
                        echo "<tr>";
                        echo sprintf("<td><img src='https://dummyimage.com/30x20/ffffff/000&text=%d' alt='block'></td>", $i + 1);
                        for ($j = 0; $j < 4; $j++) {
                            if ($board[$j][$i] == 1)
                                echo sprintf("<td id='%d %d' onclick='play(this.id)'><img src='https://dummyimage.com/30x20/000000/fff&text=%d%d' alt='block'></td>", $j, $i, $j, $i);
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
                <div id="info">
                    <p>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                            if ($_SESSION['winner'] == 'computer') {
                                echo "You lost!";
                            } else if ($_SESSION['winner'] == 'player') {
                                echo "You won!";
                            } else {
                                $counts = get_counts(unserialize($_SESSION['board']));
                                echo "<table>";
                                echo "<thead>";
                                echo "<td>Column</td><td>Length dec</td><td>Length bin</td>";
                                echo "</thead>";
                                echo "<tbody>";
                                for ($i = 0; $i < 4; $i++) {
                                    echo "<tr>";
                                    echo sprintf("<td>%d</td>", $i + 1);
                                    echo sprintf("<td>%d</td>", $counts[$i]);
                                    echo sprintf("<td>%s</td>", sprintf("%03d", decbin($counts[$i])));
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                            }
                            if ($_SESSION['winner'] != null) {
                                unset($_SESSION['board']);
                                $_SESSION['winner'] = null;
                                echo sprintf("<button onclick='location.reload();'>New Game</button>");
                            }
                        }
                        ?>
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>