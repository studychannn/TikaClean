<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/layout.php';

render_header('Tentang TikaClean', 'tentang', 'user');
?>

<main>
    <!-- Hero -->
    <section class="tentang-hero">
        <div class="container tentang-hero-content">
            <span class="badge text-bg-light text-success mb-3">Tentang Kami</span>
            <h1>TikaClean — Sistem Pelaporan Sampah Liar</h1>
            <p>Platform sederhana yang membantu warga melaporkan sampah liar dengan data lokasi yang jelas agar petugas dapat menindaklanjuti dengan cepat.</p>
        </div>
    </section>

    <!-- Tentang -->
    <section class="container py-5">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="panel">
                    <div class="section-kicker">Latar Belakang</div>
                    <h2 class="mb-3">Kenapa TikaClean dibuat?</h2>
                    <p class="text-muted">Sampah liar masih menjadi masalah umum di banyak lingkungan. Warga sering kali tidak tahu cara melaporkannya, atau laporan yang masuk tidak disertai informasi lokasi yang cukup sehingga sulit ditindaklanjuti.</p>
                    <p class="text-muted">TikaClean hadir untuk menjembatani warga dan petugas kebersihan. Dengan formulir laporan yang dilengkapi GPS dan foto, setiap laporan memiliki data yang cukup untuk langsung diproses.</p>

                    <hr class="my-4" style="border-color: var(--line);">

                    <div class="section-kicker">Tujuan</div>
                    <h3 class="h5 mb-3">Apa yang ingin dicapai?</h3>
                    <div class="feature-list">
                        <div>
                            <strong>Laporan yang terstruktur</strong>
                            <span>Setiap laporan menyertakan kategori, deskripsi, foto, dan koordinat GPS agar mudah diverifikasi.</span>
                        </div>
                        <div>
                            <strong>Bukti visual</strong>
                            <span>Foto kondisi lokasi membantu petugas memahami situasi sebelum turun ke lapangan.</span>
                        </div>
                        <div>
                            <strong>Transparansi status</strong>
                            <span>Warga dapat memantau perkembangan laporan mereka tanpa perlu menghubungi petugas secara langsung.</span>
                        </div>
                        <div>
                            <strong>Penanganan lebih cepat</strong>
                            <span>Data lokasi yang akurat membantu petugas menemukan titik sampah tanpa harus mencari-cari.</span>
                        </div>
                        <div>
                            <strong>Data terpusat</strong>
                            <span>Admin dapat memantau seluruh laporan masuk dan memperbarui statusnya dari satu dashboard.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="panel soft-panel">
                    <div class="section-kicker">Kategori Laporan</div>
                    <h3 class="h5 mb-4">Jenis sampah yang bisa dilaporkan</h3>
                    <div class="d-flex flex-column gap-2">
                        <div class="kategori-item">
                            <span class="kategori-icon">🗑️</span>
                            <div>
                                <strong>Sampah Terbuka</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.93rem;">Sampah yang dibuang sembarangan di area terbuka seperti pinggir jalan atau lahan kosong.</p>
                            </div>
                        </div>
                        <div class="kategori-item">
                            <span class="kategori-icon">📦</span>
                            <div>
                                <strong>Tumpukan Sampah</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.93rem;">Penumpukan sampah dalam jumlah besar yang sudah mengganggu lingkungan sekitar.</p>
                            </div>
                        </div>
                        <div class="kategori-item">
                            <span class="kategori-icon">🚫</span>
                            <div>
                                <strong>TPS Ilegal</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.93rem;">Tempat pembuangan sampah tidak resmi yang dibuat tanpa izin di area permukiman.</p>
                            </div>
                        </div>
                        <div class="kategori-item">
                            <span class="kategori-icon">💧</span>
                            <div>
                                <strong>Sampah di Selokan</strong>
                                <p class="text-muted mb-0 mt-1" style="font-size:0.93rem;">Sampah yang menyumbat atau mencemari saluran air dan selokan di lingkungan.</p>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-success mt-4 w-100" href="/tikaclean/#lapor">Buat Laporan Sekarang</a>
                </div>

                <div class="panel mt-4">
                    <div class="section-kicker">Kontak & Informasi</div>
                    <h3 class="h5 mb-3">Catatan untuk warga</h3>
                    <p class="text-muted" style="font-size:0.93rem;">TikaClean adalah sistem pelaporan internal. Untuk pertanyaan atau kendala teknis, hubungi pengelola layanan di lingkungan Anda.</p>
                    <ul class="text-muted ps-3 mb-0" style="font-size:0.93rem; line-height:2;">
                        <li>Laporan hanya dapat dikirim oleh pengguna terdaftar.</li>
                        <li>Foto dan koordinat GPS wajib disertakan.</li>
                        <li>Status laporan diperbarui oleh admin secara berkala.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.tentang-hero {
    background:
        linear-gradient(90deg, rgba(7,22,18,0.80), rgba(7,22,18,0.35)),
        url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
    min-height: 320px;
    display: flex;
    align-items: center;
}
.tentang-hero-content {
    color: #fff;
    padding-top: 60px;
    padding-bottom: 60px;
    max-width: 760px;
}
.tentang-hero-content h1 {
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 16px;
}
.tentang-hero-content p {
    color: rgba(255,255,255,0.88);
    font-size: 1.05rem;
    max-width: 580px;
}
.kategori-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 14px;
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 8px;
}
.kategori-icon {
    font-size: 1.4rem;
    line-height: 1;
    flex-shrink: 0;
    margin-top: 2px;
}
</style>

<?php render_footer(); ?>
