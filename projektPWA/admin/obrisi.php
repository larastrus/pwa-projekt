<?php
    session_start();

    require_once __DIR__ . '/../includes/functions.php';

    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }

    $id = (int) ($_GET['id'] ?? 0);

    obrisiPjesmu($id);

    header('Location: dashboard.php');
exit;