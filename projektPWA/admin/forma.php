<form method="POST" class="song-form">

    <input
        name="naslov"
        placeholder="Naslov"
        value="<?= h($p['naslov'] ?? '') ?>"
        required
    >

    <input
        name="izvodjac"
        placeholder="Izvođač"
        value="<?= h($p['izvodjac'] ?? '') ?>"
        required
    >

    <input
        name="album"
        placeholder="Album"
        value="<?= h($p['album'] ?? '') ?>"
    >

    <input
        name="zanr"
        placeholder="Žanr"
        value="<?= h($p['zanr'] ?? '') ?>"
    >

    <input
        type="number"
        name="godina"
        placeholder="Godina"
        value="<?= h($p['godina'] ?? '') ?>"
    >

    <input
        type="number"
        name="trajanje"
        placeholder="Trajanje u sekundama"
        value="<?= h($p['trajanje'] ?? '') ?>"
    >

    <input
        type="number"
        step="0.1"
        min="0"
        max="10"
        name="ocjena"
        placeholder="Ocjena"
        value="<?= h($p['ocjena'] ?? '') ?>"
    >

    <input
        name="cover"
        placeholder="Cover URL ili images/slika.jpg"
        value="<?= h($p['cover'] ?? '') ?>"
    >

    <textarea
        name="opis"
        placeholder="Opis pjesme"
    ><?= h($p['opis'] ?? '') ?></textarea>

    <div class="form-actions">
        <button type="submit">
            Spremi
        </button>

        <a class="btn secondary" href="dashboard.php">
            Odustani
        </a>
    </div>

</form>