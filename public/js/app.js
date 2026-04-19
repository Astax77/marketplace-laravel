/* ============================================================
   PetitesAnnonces – app.js
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ── Autocomplete search ──────────────────────────────────────
    const searchInput  = document.getElementById('searchInput');
    const autocomplete = document.getElementById('autocomplete');

    if (searchInput && autocomplete) {
        let debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const q = this.value.trim();

            if (q.length < 2) {
                autocomplete.innerHTML = '';
                autocomplete.classList.add('d-none');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/search/suggestions?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(items => {
                        autocomplete.innerHTML = '';
                        if (items.length === 0) {
                            autocomplete.classList.add('d-none');
                            return;
                        }
                        items.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action small py-2';
                            li.innerHTML = `<i class="bi bi-search me-2 text-muted"></i>${escapeHtml(item.label)}`;
                            li.addEventListener('click', () => { window.location.href = item.url; });
                            autocomplete.appendChild(li);
                        });
                        autocomplete.classList.remove('d-none');
                    })
                    .catch(() => autocomplete.classList.add('d-none'));
            }, 280);
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !autocomplete.contains(e.target)) {
                autocomplete.classList.add('d-none');
            }
        });
    }

    // ── Auto-dismiss flash alerts ────────────────────────────────
    document.querySelectorAll('.alert.alert-success, .alert.alert-info').forEach(el => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
        }, 4500);
    });

    // ── Confirm before form with data-confirm ───────────────────
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

    // ── Image preview for file inputs ───────────────────────────
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        const targetId = input.dataset.preview;
        const target   = document.getElementById(targetId);
        if (!target) return;

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { target.src = e.target.result; };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});

/* ── Utility ────────────────────────────────────────────────── */
function escapeHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}
