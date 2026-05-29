<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/layout.php';

$pdo = get_db();
$categories = ['Sampah Terbuka', 'Tumpukan Sampah', 'TPS Ilegal', 'Sampah di Selokan'];
render_header('Lapor Sampah Liar', 'home', 'user');
?>

<main>
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active hero-slide" style="background-image: url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=1600&q=80');">
                    <div class="container hero-content">
                        <span class="badge text-bg-light text-success mb-3">TikaClean</span>
                        <h1>Bantu lingkungan tetap bersih dari sampah liar.</h1>
                        <p>Laporkan tumpukan sampah, TPS ilegal, atau sampah yang mengganggu lingkungan langsung dari lokasi kejadian.</p>
                        <a class="btn btn-success btn-lg" href="#lapor">Buat Laporan</a>
                    </div>
                </div>
                <div class="carousel-item hero-slide" style="background-image: url('https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&w=1600&q=80');">
                    <div class="container hero-content">
                        <span class="badge text-bg-light text-success mb-3">Lokasi Akurat</span>
                        <h1>Petugas lebih mudah menemukan titik laporan.</h1>
                        <p>Gunakan GPS browser untuk menyimpan koordinat lokasi tanpa mengetik alamat panjang.</p>
                        <a class="btn btn-success btn-lg" href="/tikaclean/tracking.php">Cek Tracking</a>
                    </div>
                </div>
                <div class="carousel-item hero-slide" style="background-image: url('https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?auto=format&fit=crop&w=1600&q=80');">
                    <div class="container hero-content">
                        <span class="badge text-bg-light text-success mb-3">Pantau Progres</span>
                        <h1>Lihat perkembangan laporan kapan saja.</h1>
                        <p>Status laporan diperbarui oleh petugas agar warga tahu proses penanganannya.</p>
                        <a class="btn btn-success btn-lg" href="/tikaclean/tracking.php">Lihat Status</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Berikutnya</span>
            </button>
        </div>
    </section>

    <section class="section-alt py-5" id="cara-kerja">
        <div class="container">
            <div class="page-heading text-center mx-auto" style="max-width: 560px;">
                <div class="section-kicker">Cara Kerja</div>
                <h2 class="h3 fw-800 mb-2">Lapor dari lokasi kejadian, lalu pantau progresnya.</h2>
                <p class="text-muted mb-0">TikaClean dibuat agar warga bisa menyampaikan laporan dengan data yang jelas dan mudah ditindaklanjuti.</p>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="step-card panel">
                        <span>1</span>
                        <h3 class="h5 mb-1">Masuk atau daftar</h3>
                        <p class="text-muted mb-0">Buat akun agar laporan Anda tersimpan dan dapat dipantau oleh petugas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card panel">
                        <span>2</span>
                        <h3 class="h5 mb-1">Ambil titik lokasi</h3>
                        <p class="text-muted mb-0">Gunakan tombol GPS di form untuk menyimpan koordinat otomatis dari lokasi kejadian.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card panel">
                        <span>3</span>
                        <h3 class="h5 mb-1">Pantau progres</h3>
                        <p class="text-muted mb-0">Cek status laporan di halaman tracking hingga sampah ditangani.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5" id="lapor">
        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success shadow-sm"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php elseif (!empty($_GET['error'])): ?>
            <div class="alert alert-danger shadow-sm"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="panel">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
                        <div>
                            <div class="section-kicker">Buat Laporan</div>
                            <h2 class="h3 mb-1">Ceritakan kondisi sampah di sekitar kamu</h2>
                            <p class="text-muted mb-0">Lengkapi semua kolom agar laporan mudah diverifikasi petugas.</p>
                        </div>
                        <a class="btn btn-outline-success btn-sm align-self-start" href="/tikaclean/tracking.php">Cek Status Laporan</a>
                    </div>
                    <?php if (is_user_logged_in()): ?>
                        <div class="alert alert-success">Kamu masuk sebagai <strong><?= htmlspecialchars(current_user_name()) ?></strong>. Laporan akan tersimpan di akun ini.</div>
                    <?php else: ?>
                        <div class="alert alert-warning">Masuk atau daftar terlebih dahulu untuk mengirim laporan baru.</div>
                    <?php endif; ?>

                    <form action="/tikaclean/submit.php" method="post" enctype="multipart/form-data">
                        <?php if (is_user_logged_in()): ?>
                            <div class="mb-3">
                                <label class="form-label">Nama Pelapor</label>
                                <input class="form-control" type="text" value="<?= htmlspecialchars(current_user_name()) ?>" readonly>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Kategori Sampah</label>
                            <select class="form-select" name="category" required>
                                <option value="">Pilih jenis</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Lokasi</label>
                            <textarea class="form-control" name="description" rows="4" required placeholder="Contoh: tumpukan sampah berada di pinggir jalan dekat selokan, sudah menimbulkan bau."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Lokasi</label>
                            <input class="form-control" type="file" name="photo" accept="image/*" required>
                        </div>

                        <div class="accordion mb-3" id="accordionGPS">
                            <div class="accordion-item" style="border-color: var(--line); border-radius: 8px;">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanGPS" aria-expanded="false" aria-controls="panduanGPS" style="border-radius: 8px; font-weight: 700; color: var(--brand-dark); background: #ecfdf3;">
                                        💡 Butuh bantuan menggunakan GPS?
                                    </button>
                                </h2>
                                <div id="panduanGPS" class="accordion-collapse collapse" data-bs-parent="#accordionGPS">
                                    <div class="accordion-body" style="font-size: 0.93rem; color: #334155;">
                                        <ol class="mb-2 ps-3">
                                            <li>Pastikan Anda sudah berada di lokasi sampah liar.</li>
                                            <li>Tekan tombol <strong>Ambil Titik Lokasi</strong> di bawah.</li>
                                            <li>Izinkan browser mengakses lokasi saat diminta.</li>
                                            <li>Kolom Latitude dan Longitude akan terisi otomatis.</li>
                                            <li>Klik <strong>Kirim Laporan</strong> setelah semua data lengkap.</li>
                                        </ol>
                                        <p class="mb-0 text-muted">Jika izin lokasi tidak muncul, muat ulang halaman dan pastikan GPS perangkat Anda aktif.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input class="form-control" type="text" id="latitude" name="latitude" readonly placeholder="Latitude - pilih Ambil Titik Lokasi">
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="text" id="longitude" name="longitude" readonly placeholder="Longitude - pilih Ambil Titik Lokasi">
                            </div>
                        </div>
                        <div id="gps-feedback" class="mb-3" aria-live="polite"></div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <?php if (is_user_logged_in()): ?>
                                <button class="btn btn-outline-success" type="button" id="get-location">Ambil Titik Lokasi</button>
                                <button class="btn btn-success" type="submit">Kirim Laporan</button>
                            <?php else: ?>
                                <a class="btn btn-success" href="/tikaclean/user/login.php">Login untuk Melapor</a>
                                <a class="btn btn-outline-success" href="/tikaclean/user/register.php">Register</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="/tikaclean/assets/script.js"></script>
<?php render_footer(); ?>
