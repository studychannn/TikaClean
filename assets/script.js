document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('get-location');
    if (!btn) return;

    const feedback = document.getElementById('gps-feedback');

    btn.addEventListener('click', () => {
        const latInput = document.getElementById('latitude');
        const longInput = document.getElementById('longitude');
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser ini.');
            return;
        }

        btn.textContent = 'Mengambil lokasi...';
        btn.disabled = true;
        if (feedback) {
            feedback.textContent = '';
            feedback.className = '';
        }

        navigator.geolocation.getCurrentPosition((position) => {
            latInput.value = position.coords.latitude.toFixed(6);
            longInput.value = position.coords.longitude.toFixed(6);
            btn.textContent = '✓ Lokasi Berhasil Diambil';
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-success');
            btn.disabled = false;
            if (feedback) {
                feedback.textContent = 'Koordinat GPS sudah tersimpan. Silakan lanjut kirim laporan.';
                feedback.className = 'gps-feedback-ok';
            }
        }, () => {
            btn.textContent = 'Ambil Titik Lokasi';
            btn.disabled = false;
            if (feedback) {
                feedback.textContent = 'Gagal mengambil lokasi. Pastikan GPS aktif dan izin lokasi diberikan.';
                feedback.className = 'gps-feedback-err';
            }
        }, { enableHighAccuracy: true, timeout: 10000 });
    });
});
