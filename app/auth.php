<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function is_user_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function current_user_name(): string
{
    return $_SESSION['user_name'] ?? '';
}

function require_admin(): void
{
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_user(): void
{
    if (!is_user_logged_in()) {
        header('Location: /tikaclean/user/login.php');
        exit;
    }
}

function admin_login(string $username, string $password): bool
{
    $adminUsername = 'admin';
    $adminPassword = 'admin123';
    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function user_register(PDO $pdo, string $name, string $email, string $password): bool
{
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return false;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$name, $email, $hash]);

    $_SESSION['user_id'] = (int)$pdo->lastInsertId();
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;

    return true;
}

function user_login(PDO $pdo, string $email, string $password): bool
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        return true;
    }

    return false;
}

function user_logout(): void
{
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
}

function admin_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
