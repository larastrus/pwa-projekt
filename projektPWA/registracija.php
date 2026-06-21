<?php
    session_start();

    require_once __DIR__ . '/includes/functions.php';

    $poruka = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $ok = registracija(
            $_POST['korisnicko_ime'] ?? '',
            $_POST['lozinka'] ?? ''
        );

        $poruka = $ok
            ? 'Registracija je uspješna. Sada se možeš prijaviti.'
            : 'Korisničko ime već postoji.';
    }
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>Registracija</title>

        <link rel="stylesheet" href="style.css">
    </head>
    <body class="auth-body">
        <div class="auth-card">

            <h1>Registracija</h1>

            <?php if ($poruka): ?>
                <div class="alert">
                    <?= h($poruka) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">

                <input
                    name="korisnicko_ime"
                    placeholder="Korisničko ime"
                    required
                >

                <input
                    type="password"
                    name="lozinka"
                    placeholder="Lozinka"
                    required
                >

                <button type="submit">
                    Registriraj se
                </button>

            </form>

            <a href="login.php">
                Već imam račun
            </a>
        </div>
    </body>
</html>