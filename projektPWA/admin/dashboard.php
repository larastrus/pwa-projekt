<?php
    session_start();

    require_once __DIR__ . '/../includes/functions.php';

    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }

    $korisnik = is_array($_SESSION['user'])
        ? ($_SESSION['user']['korisnicko_ime'] ?? 'admin')
        : $_SESSION['user'];

    $pjesme = dohvatiSvePjesme();
    $stat = statistika();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Admin panel</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<header>
    <a class="brand" href="../index.php">
        <span class="brand-icon">♫</span>
        Music Database
    </a>

    <nav>
        <a href="../index.php">Početna</a>
        <a href="dodaj.php" class="nav-pill">Dodaj pjesmu</a>
        <a href="../logout.php">Odjava</a>
    </nav>
</header>

<main class="content admin">
    <section class="panel">
        <div class="section-head">
            <div>
                <h2>Admin dashboard</h2>
                <p>
                    Prijavljen/a: <?= h($korisnik) ?> · Upravljanje MySQL bazom.
                </p>
            </div>

            <a class="btn primary" href="dodaj.php">+ Nova pjesma</a>
        </div>

        <section class="stats inside">
            <div class="stat">
                <b><?= h($stat['broj']) ?></b>
                <span>Pjesama</span>
            </div>

            <div class="stat">
                <b><?= h($stat['zanrovi']) ?></b>
                <span>Žanrova</span>
            </div>

            <div class="stat">
                <b><?= h($stat['prosjek']) ?></b>
                <span>Prosjek</span>
            </div>
        </section>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Naslov</th>
                    <th>Izvođač</th>
                    <th>Žanr</th>
                    <th>Ocjena</th>
                    <th>Akcije</th>
                </tr>

                <?php foreach ($pjesme as $p): ?>
                    <tr>
                        <td><?= h($p['id']) ?></td>
                        <td><?= h($p['naslov']) ?></td>
                        <td><?= h($p['izvodjac']) ?></td>
                        <td><?= h($p['zanr']) ?></td>
                        <td>★ <?= h($p['ocjena']) ?></td>
                        <td>
                            <a href="uredi.php?id=<?= h($p['id']) ?>">Uredi</a>
                            ·
                            <a
                                class="danger"
                                href="obrisi.php?id=<?= h($p['id']) ?>"
                                onclick="return confirm('Obrisati pjesmu?')"
                            >
                                Obriši
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</main>

</body>
</html>