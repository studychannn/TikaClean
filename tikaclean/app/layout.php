<?php
function render_header(string $title, string $active = 'home', string $area = 'user'): void
{
    $isAdmin = $area === 'admin';
    $isAdminLogin = $area === 'admin-login';
    $isLogin = $area === 'login';
    $isUserLoggedIn = function_exists('is_user_logged_in') && is_user_logged_in();
    $baseUrl = '/tikaclean/';
    $homeUrl = $baseUrl;
    $trackingUrl = $baseUrl . 'tracking.php';
    $aboutUrl = $baseUrl . 'user/tentang.php';
    $laporanSayaUrl = $baseUrl . 'user/laporan-saya.php';
    $userLoginUrl = $baseUrl . 'user/login.php';
    $userRegisterUrl = $baseUrl . 'user/register.php';
    $userLogoutUrl = $baseUrl . 'user/logout.php';
    $adminUrl = $baseUrl . 'admin/';
    $adminLoginUrl = $baseUrl . 'admin/login.php';
    $adminLogoutUrl = $baseUrl . 'admin/logout.php';
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= htmlspecialchars($title) ?> - TikaClean</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $baseUrl ?>assets/style.css">
        <link rel="icon" type="image/svg+xml" href="<?= $baseUrl ?>assets/favicon.svg">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
            <div class="container">
                <a class="navbar-brand" href="<?= ($isAdmin || $isAdminLogin) ? $adminUrl : $homeUrl ?>">
                    <img src="<?= $baseUrl ?>assets/logo.svg" alt="TikaClean" height="42" style="display:block;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        <?php if ($isAdmin): ?>
                            <li class="nav-item"><a class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= $adminUrl ?>">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>">Lihat Situs</a></li>
                            <li class="nav-item"><a class="btn btn-outline-success btn-sm px-3" href="<?= $adminLogoutUrl ?>">Logout</a></li>
                        <?php elseif ($isAdminLogin): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>">Lihat Situs</a></li>
                            <li class="nav-item"><a class="btn btn-outline-success btn-sm px-3 active" href="<?= $adminLoginUrl ?>">Login Admin</a></li>
                        <?php elseif ($isLogin): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>">Kembali ke Situs</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link <?= $active === 'home' ? 'active' : '' ?>" href="<?= $homeUrl ?>">Beranda</a></li>
                            <li class="nav-item"><a class="nav-link <?= $active === 'tentang' ? 'active' : '' ?>" href="<?= $aboutUrl ?>">Tentang</a></li>
                            <li class="nav-item"><a class="nav-link <?= $active === 'tracking' ? 'active' : '' ?>" href="<?= $trackingUrl ?>">Tracking</a></li>
                            <?php if ($isUserLoggedIn): ?>
                                <li class="nav-item"><a class="nav-link <?= $active === 'laporan-saya' ? 'active' : '' ?>" href="<?= $laporanSayaUrl ?>">Laporan Saya</a></li>
                                <li class="nav-item"><span class="nav-link text-muted">Halo, <?= htmlspecialchars(current_user_name()) ?></span></li>
                                <li class="nav-item"><a class="btn btn-outline-success btn-sm px-3" href="<?= $userLogoutUrl ?>">Logout</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link <?= $active === 'login' ? 'active' : '' ?>" href="<?= $userLoginUrl ?>">Login</a></li>
                                <li class="nav-item"><a class="btn btn-outline-success btn-sm px-3" href="<?= $userRegisterUrl ?>">Register</a></li>
                            <?php endif; ?>
                            <li class="nav-item"><a class="btn btn-success btn-sm px-3" href="<?= $homeUrl ?>#lapor">Lapor Sekarang</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    <?php
}

function render_back_button(string $href, string $label = 'Kembali'): void
{
    ?>
        <div class="mb-3">
            <a class="back-link" href="<?= htmlspecialchars($href) ?>">&larr; <?= htmlspecialchars($label) ?></a>
        </div>
    <?php
}

function render_footer(string $area = 'user'): void
{
    $baseUrl = '/tikaclean/';
    $isAdmin = $area === 'admin';
    ?>
        <footer class="site-footer">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <a class="footer-brand" href="<?= $isAdmin ? $baseUrl . 'admin/' : $baseUrl ?>">
                            <img src="<?= $baseUrl ?>assets/logo.svg" alt="TikaClean" height="48" style="filter: brightness(0) invert(1); display:block;">
                        </a>
                        <p class="footer-text mt-3 mb-0">Layanan pelaporan lingkungan untuk membantu warga menyampaikan temuan sampah liar dengan lokasi yang jelas dan bukti foto.</p>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h2 class="footer-title">Navigasi</h2>
                        <ul class="footer-links">
                            <li><a href="<?= $baseUrl ?>">Beranda</a></li>
                            <li><a href="<?= $baseUrl ?>user/tentang.php">Tentang</a></li>
                            <li><a href="<?= $baseUrl ?>#cara-kerja">Cara Kerja</a></li>
                            <li><a href="<?= $baseUrl ?>tracking.php">Tracking</a></li>
                            <li><a href="<?= $baseUrl ?>#lapor">Buat Laporan</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h2 class="footer-title">Akses</h2>
                        <ul class="footer-links">
                            <?php if ($isAdmin): ?>
                                <li><a href="<?= $baseUrl ?>admin/">Dashboard</a></li>
                                <li><a href="<?= $baseUrl ?>admin/logout.php">Logout Admin</a></li>
                            <?php else: ?>
                                <li><a href="<?= $baseUrl ?>user/login.php">Login User</a></li>
                                <li><a href="<?= $baseUrl ?>user/register.php">Register</a></li>
                            <?php endif; ?>
                            <li><a href="<?= $baseUrl ?>admin/login.php">Login Admin</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <h2 class="footer-title">Layanan</h2>
                        <p class="footer-text mb-2">Laporan masuk dipantau oleh admin dan status penanganannya dapat dilihat kembali oleh warga.</p>
                        <span class="footer-badge">Lingkungan lebih tertib</span>
                    </div>
                </div>
                <div class="footer-bottom">
                    <span>&copy; <?= date('Y') ?> TikaClean</span>
                    <span>Sistem Pelaporan Sampah Liar</span>
                </div>
            </div>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?>
