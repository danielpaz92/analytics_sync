<?php

function conn() {
    $host = '';
    $user = '';
    $password = '';
    $database = '';

    $mysqli = new mysqli($host, $user, $password, $database);
    $mysqli->set_charset('utf8');
    return $mysqli;
}

?>