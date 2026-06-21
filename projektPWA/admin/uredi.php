<?php
    session_start();

    require_once __DIR__ . '/../includes/functions.php';

    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }

    $id = (int) ($_GET['id'] ?? 0);

    $p = dohvatiPjesmuPoId($id);

    if (!$p) {
        die('Pjesma nije pronađena.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        azurirajPjesmu($id, $_POST);

        header('Location: dashboard.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>Uredi pjesmu</title>

        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <a class="brand" href="../index.php">
                <span class="brand-icon">♫</span>
                MusicHub
            </a>

            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="../logout.php">Odjava</a>
            </nav>
        </header>
        <main class="content narrow">
            <section class="panel form-panel">
                <h2>Uredi pjesmu</h2>

                <?php include __DIR__ . '/forma.php'; ?>
            </section>
        </main>
    </body>
</html>