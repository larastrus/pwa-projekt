function esc(text) {
    return String(text ?? '').replace(/[&<>"']/g, function (m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m];
    });
}

function openSong(p) {
    const modal = document.getElementById('modalOverlay');
    const body = document.getElementById('modalBody');

    if (!modal || !body) {
        return;
    }

    const min = Math.floor(Number(p.trajanje) / 60);
    const sek = String(Number(p.trajanje) % 60).padStart(2, '0');

    body.innerHTML = `
        <img 
            class="modal-cover" 
            src="${esc(p.cover)}" 
            alt="${esc(p.naslov)}"
            onerror="this.src='https://placehold.co/500x500/fff0f7/e22d7f?text=♪'"
        >

        <div>
            <h2>${esc(p.naslov)}</h2>
            <div class="artist">♫ ${esc(p.izvodjac)}</div>

            <div class="chips">
                <span class="chip">${esc(p.zanr)}</span>
                <span class="chip">${esc(p.godina)}</span>
                <span class="chip">${min}:${sek}</span>
                <span class="chip">★ ${esc(p.ocjena)}</span>
            </div>

            <p><b>Album:</b> ${esc(p.album)}</p>
            <p>${esc(p.opis)}</p>

            <div class="api-links">
                <a href="api/index.php?id=${esc(p.id)}&format=xml" target="_blank">XML prikaz</a>
                <a href="api/index.php?id=${esc(p.id)}&format=json" target="_blank">JSON prikaz</a>
            </div>
        </div>
    `;

    modal.classList.add('open');
}

function closeModal(e) {
    if (!e || e.target.id === 'modalOverlay' || e.target.classList.contains('modal-close')) {
        document.getElementById('modalOverlay').classList.remove('open');
    }
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});