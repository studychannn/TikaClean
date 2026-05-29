<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
$pdo = get_db();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /tikaclean/');
    exit;
}

require_user();

$userId = (int)$_SESSION['user_id'];
$name = current_user_name();
$category = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');
$latitude = trim($_POST['latitude'] ?? '');
$longitude = trim($_POST['longitude'] ?? '');
$status = 'Menunggu';

$errors = [];
if ($name === '' || $category === '' || $description === '') {
    $errors[] = 'Lengkapi semua bidang.';
}
if ($latitude === '' || $longitude === '') {
    $errors[] = 'Lokasi GPS harus diambil sebelum mengirim.';
}

// Validasi foto — tipe file + ekstensi
$allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$allowedExt  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Foto kondisi diperlukan.';
} else {
    $photo    = $_FILES['photo'];
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($photo['tmp_name']);
    $ext      = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION)); // #2 lowercase

    if (!in_array($mimeType, $allowedMime, true) || !in_array($ext, $allowedExt, true)) {
        $errors[] = 'File foto tidak valid. Gunakan format JPG, PNG, atau WebP.';
    }
}

if ($errors) {
    header('Location: /tikaclean/?error=' . urlencode(implode(' ', $errors)));
    exit;
}

$uploadDir = __DIR__ . '/../uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename   = 'report_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
move_uploaded_file($photo['tmp_name'], $targetPath);

$stmt = $pdo->prepare('INSERT INTO reports (user_id, name, category, description, photo, latitude, longitude, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
$stmt->execute([$userId, $name, $category, $description, $filename, $latitude, $longitude, $status]);

// #4 — sertakan ID laporan di pesan sukses
$newId = $pdo->lastInsertId();
header('Location: /tikaclean/?success=' . urlencode('Laporan #' . $newId . ' berhasil dikirim. Simpan nomor ini untuk tracking.'));
exit;
