<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'music_db';

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die('Greška kod spajanja na bazu: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
?>
