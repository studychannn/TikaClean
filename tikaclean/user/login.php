<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/layout.php';

if (is_user_logged_in()) {
    header('Location: /tikaclean/');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (user_login(get_db(), $email, $password)) {
        header('Location: /tikaclean/');
        exit;
    }

    $message = 'Email atau password salah.';
}

render_header('Login User', 'login', 'user');
?>

<main class="auth-page">
    <section class="auth-card">
        <?php render_back_button('/tikaclean/', 'Kembali ke Beranda'); ?>
        <div class="section-kicker">Akun Warga</div>
        <h1 class="h3 mb-2">Masuk ke TikaClean</h1>
        <p class="text-muted mb-4">Masuk untuk mengirim laporan sampah liar dengan lokasi GPS dan memantau tindak lanjutnya.</p>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="/tikaclean/user/login.php" method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" required placeholder="nama@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control" type="password" name="password" required placeholder="Password">
            </div>
            <button class="btn btn-success w-100" type="submit">Login</button>
        </form>

        <p class="text-muted text-center mt-4 mb-0">Belum punya akun? <a href="/tikaclean/user/register.php">Register</a></p>
    </section>
</main>

<?php render_footer(); ?>
