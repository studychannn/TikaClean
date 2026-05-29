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
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || strlen($password) < 6) {
        $message = 'Nama, email, dan password minimal 6 karakter wajib diisi.';
    } elseif (user_register(get_db(), $name, $email, $password)) {
        header('Location: /tikaclean/');
        exit;
    } else {
        $message = 'Email sudah terdaftar.';
    }
}

render_header('Register User', 'login', 'user');
?>

<main class="auth-page">
    <section class="auth-card">
        <?php render_back_button('/tikaclean/user/login.php', 'Kembali ke Login'); ?>
        <div class="section-kicker">Akun Warga</div>
        <h1 class="h3 mb-2">Daftar Akun Baru</h1>
        <p class="text-muted mb-4">Daftarkan akun untuk mengirim laporan sampah dan melihat update penanganannya.</p>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="/tikaclean/user/register.php" method="post">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input class="form-control" type="text" name="name" required placeholder="Nama Anda">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" required placeholder="nama@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control" type="password" name="password" minlength="6" required placeholder="Minimal 6 karakter">
            </div>
            <button class="btn btn-success w-100" type="submit">Register</button>
        </form>

        <p class="text-muted text-center mt-4 mb-0">Sudah punya akun? <a href="/tikaclean/user/login.php">Login</a></p>
    </section>
</main>

<?php render_footer(); ?>
