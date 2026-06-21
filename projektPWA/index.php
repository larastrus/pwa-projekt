<?php
    session_start();

    require_once __DIR__ . '/includes/functions.php';

    $pretraga = $_GET['pretraga'] ?? '';
    $zanr = $_GET['zanr'] ?? '';
    $sort = $_GET['sort'] ?? 'ocjena';

    $pjesme = dohvatiSvePjesme($pretraga, $zanr, $sort);
    $zanrovi = sviZanrovi();
    $stat = statistika();
    $top = topIzvodjac();
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MusicDB - PWA projekt</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                <a href="#pjesme">Kolekcija</a>
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

        <section class="hero">
            <div class="hero-inner">
                <div class="pill">PWA PROJEKT</div>

                <h1>
                    Glazbena kolekcija
                    <span>u MySQL bazi</span>
                </h1>

                <p>
                    Istraži glazbenu bazu, filtriraj sadržaj i upravljaj podacima putem admin sustava.
                </p>

                <div class="actions">
                    <a href="#pjesme" class="btn primary">Pregledaj kolekciju</a>

                    <?php if (!isLoggedIn()): ?>
                        <a href="login.php" class="btn secondary">Admin login</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="stats">
            <div class="stat">
                <b><?= h($stat['broj']) ?></b>
                <span>Pjesama u bazi</span>
            </div>

            <div class="stat">
                <b><?= h($stat['zanrovi']) ?></b>
                <span>Žanrova</span>
            </div>

            <div class="stat">
                <b><?= h($stat['prosjek'] ?? '0.0') ?></b>
                <span>Prosječna ocjena</span>
            </div>

            <div class="stat accent">
                <b><?= h($top['izvodjac'] ?? '-') ?></b>
                <span>Top izvođač</span>
            </div>
        </section>

        <main class="content" id="pjesme">
            <section class="panel glass">
                <div class="panel-top">
                    <div>
                        <h2>Glazbena baza</h2>
                        <p>Podaci se dohvaćaju iz MySQL tablice <b>pjesme</b>.</p>
                    </div>

                    <form class="filters" method="GET">
                        <input
                            type="text"
                            name="pretraga"
                            placeholder="Pretraži pjesme, izvođače, albume..."
                            value="<?= h($pretraga) ?>"
                        >

                        <button type="submit">Traži</button>

                        <select name="zanr" onchange="this.form.submit()">
                            <option value="">Svi žanrovi</option>

                            <?php foreach ($zanrovi as $z): ?>
                                <option value="<?= h($z) ?>" <?= $zanr === $z ? 'selected' : '' ?>>
                                    <?= h($z) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select name="sort" onchange="this.form.submit()">
                            <option value="ocjena" <?= $sort === 'ocjena' ? 'selected' : '' ?>>
                                Najbolje ocjene
                            </option>
                            <option value="godina" <?= $sort === 'godina' ? 'selected' : '' ?>>
                                Najnovije
                            </option>
                            <option value="naslov" <?= $sort === 'naslov' ? 'selected' : '' ?>>
                                A-Z
                            </option>
                        </select>
                    </form>
                </div>

                <div class="section-head" id="rezultati">
                    <div>
                        <h2>Rezultati</h2>
                        <p>Prikazano je <?= count($pjesme) ?> rezultata.</p>
                    </div>

                    <?php if (isAdmin()): ?>
                        <a class="btn small primary" href="admin/dodaj.php">+ Dodaj pjesmu</a>
                    <?php endif; ?>
                </div>

                <?php if (empty($pjesme)): ?>
                    <div class="empty">Nema rezultata.</div>
                <?php else: ?>
                    <div class="grid">
                        <?php foreach ($pjesme as $i => $p): ?>
                            <article
                                class="song"
                                onclick='openSong(<?= json_encode(
                                    $p,
                                    JSON_UNESCAPED_UNICODE |
                                    JSON_HEX_APOS |
                                    JSON_HEX_QUOT |
                                    JSON_HEX_TAG |
                                    JSON_HEX_AMP
                                ) ?>)'
                            >
                                <div class="cover-wrap">
                                    <img
                                        src="<?= h($p['cover']) ?>"
                                        alt="<?= h($p['naslov']) ?>"
                                        onerror="this.src='https://placehold.co/500x500/fff0f7/e22d7f?text=♪'"
                                    >

                                    <div class="rank"><?= $i + 1 ?></div>
                                </div>

                                <div class="song-body">
                                    <h3><?= h($p['naslov']) ?></h3>
                                    <div class="artist"><?= h($p['izvodjac']) ?></div>
                                    <div class="album"><?= h($p['album']) ?></div>

                                    <span class="chip"><?= h($p['zanr']) ?></span>

                                    <div class="meta">
                                        <span>
                                            <?= h($p['godina']) ?> · <?= formatirajTrajanje($p['trajanje']) ?>
                                        </span>
                                        <span class="score">★ <?= h($p['ocjena']) ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
            <div class="modal">
                <button class="modal-close" onclick="closeModal()">×</button>
                <div class="modal-body" id="modalBody"></div>
            </div>
        </div>

        <footer>
            PPDI/PWA projekt · MySQL baza · XML i JSON API ·
            <a href="database/music_db.sql">SQL skripta</a>
        </footer>

        <script src="script.js"></script>

            <script>
            window.addEventListener('load', function () {
                const params = new URLSearchParams(window.location.search);

                if (params.has('pretraga') || params.has('zanr') || params.has('sort')) {
                    document.getElementById('rezultati')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        </script>
    </body>
</html>