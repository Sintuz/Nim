<?php
session_start();

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $board = unserialize($_SESSION['table']);

    $coords = explode(" ", $_POST['id']);

    $x = intval($coords[0]);
    $y = intval($coords[1]);

    if($board[$x][$y] == 1) {
        for($i=$y;$i<6; $i++) {
            $board[$x][$i]=0;
        }


        $_SESSION['table'] = serialize($board);
        http_response_code(200);
    } else {
        
    }



}

?>