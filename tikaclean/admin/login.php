<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/layout.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (admin_login($username, $password)) {
        header('Location: index.php');
        exit;
    }
    $message = 'Username atau password salah.';
}

render_header('Login Admin', 'login', 'admin-login');
?>

<main class="auth-page">
    <section class="auth-card">
        <?php render_back_button('/tikaclean/', 'Kembali ke Situs'); ?>
        <div class="section-kicker">Area Admin</div>
        <h1 class="h3 mb-2">Masuk Dashboard</h1>
        <p class="text-muted mb-4">Halaman ini khusus petugas untuk mengelola laporan TikaClean.</p>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input class="form-control" type="text" name="username" required placeholder="Username admin">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control" type="password" name="password" required placeholder="Password admin">
            </div>
            <button class="btn btn-success w-100" type="submit">Masuk</button>
        </form>
    </section>
</main>

<?php render_footer('admin'); ?>
