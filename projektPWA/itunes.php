<?php
    session_start();

    require_once __DIR__ . '/includes/functions.php';

    $upit = $_GET['upit'] ?? '';
    $rez = $upit ? itunesPretraga($upit) : [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
        dodajPjesmu($_POST);

        header('Location: index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>iTunes API</title>

        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
                <a href="index.php" class="brand">
                    <span class="brand-icon">♫</span>
                    <span>Music Database</span>
                </a>

                <nav>
                    <a href="index.php">Početna</a>
                    <a href="index.php#pjesme">Kolekcija</a>
                    <a href="itunes.php">iTunes API</a>
                    <a href="api/index.php?format=xml">XML</a>
                    <a href="api/index.php?format=json">JSON</a>

                    <?php if (isLoggedIn()): ?>
                        <a href="admin/dashboard.php" class="nav-pill">Admin</a>
                        <a href="logout.php">Odjava</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-pill">Login</a>
                    <?php endif; ?>
                </nav>
        </header>
        <main class="content">
            <section class="panel">
                <div class="panel-top">
                    <div>
                        <h2>iTunes API pretraga</h2>
                        <p>Dohvati pjesme iz vanjskog API-ja i spremi ih u MySQL bazu.</p>
                    </div>

                    <form class="filters simple" method="GET">
                        <input
                            name="upit"
                            placeholder="npr. Coldplay Yellow"
                            value="<?= h($upit) ?>"
                            required
                        >

                        <button type="submit">Pretraži</button>
                    </form>
                </div>

                <?php if ($upit && empty($rez)): ?>
                    <div class="empty">Nema rezultata.</div>
                <?php endif; ?>

                <div class="grid">
                    <?php foreach ($rez as $p): ?>
                        <article class="song">
                            <div class="cover-wrap">
                                <img src="<?= h($p['cover']) ?>" alt="<?= h($p['naslov']) ?>">
                            </div>

                            <div class="song-body">
                                <h3><?= h($p['naslov']) ?></h3>

                                <div class="artist"><?= h($p['izvodjac']) ?></div>
                                <div class="album"><?= h($p['album']) ?></div>

                                <span class="chip"><?= h($p['zanr']) ?></span>

                                <?php if (isLoggedIn()): ?>
                                    <form method="POST" class="save-api">
                                        <?php foreach ($p as $k => $v): ?>
                                            <input
                                                type="hidden"
                                                name="<?= h($k) ?>"
                                                value="<?= h($v) ?>"
                                            >
                                        <?php endforeach; ?>

                                        <button type="submit">Spremi u MySQL</button>
                                    </form>
                                <?php else: ?>
                                    <p>
                                        <a href="login.php">Prijavi se za spremanje</a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </body>
</html>