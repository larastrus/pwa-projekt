<?php
    session_start();
    require_once __DIR__ . '/includes/functions.php';

    $greska = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $user = login($_POST['korisnicko_ime'] ?? '', $_POST['lozinka'] ?? '');

        if($user){
            $_SESSION['user'] = $user;
            header('Location: admin/dashboard.php');
            exit;
        }

        $greska = 'Pogrešno korisničko ime ili lozinka.';
    }
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="auth-body">
        <div class="auth-card">
            <a class="brand auth-brand" href="index.php">
                <span class="brand-icon">♫</span>
                Music Database
            </a>

            <h1>Prijava u sustav</h1>
            <p>Admin podaci: <b>admin</b> / <b>1234</b></p>

            <?php if($greska): ?>
                <div class="alert"><?= h($greska) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <input name="korisnicko_ime" placeholder="Korisničko ime" required>
                <input type="password" name="lozinka" placeholder="Lozinka" required>
                <button>Prijavi se</button>
            </form>

            <a href="registracija.php">Napravi korisnički račun</a>
        </div>
    </body>
</html>