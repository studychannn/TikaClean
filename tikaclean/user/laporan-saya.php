<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/layout.php';

require_user();

$pdo    = get_db();
$userId = (int)$_SESSION['user_id'];

$allowedStatuses = ['Menunggu', 'Diproses', 'Selesai'];
$filterStatus    = $_GET['status'] ?? '';

$where  = ['user_id = ?'];
$params = [$userId];
if ($filterStatus && in_array($filterStatus, $allowedStatuses, true)) {
    $where[]  = 'status = ?';
    $params[] = $filterStatus;
}

$sql     = 'SELECT * FROM reports WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC';
$stmt    = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung per status milik user ini
$counts = array_fill_keys($allowedStatuses, 0);
$allStmt = $pdo->prepare('SELECT status, COUNT(*) as c FROM reports WHERE user_id = ? GROUP BY status');
$allStmt->execute([$userId]);
foreach ($allStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $counts[$row['status']] = (int)$row['c'];
}

render_header('Laporan Saya', 'laporan-saya', 'user');
?>

<main class="container py-5">
    <?php render_back_button('/tikaclean/', 'Kembali ke Beranda'); ?>
    <div class="page-heading">
        <div class="section-kicker">Akun Saya</div>
        <h1>Laporan Saya</h1>
        <p class="text-muted">Semua laporan yang pernah Anda kirimkan beserta status penanganannya.</p>
    </div>

    <!-- Mini stat -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <span>Total</span>
                <strong><?= array_sum($counts) ?></strong>
            </div>
        </div>
        <?php foreach ($counts as $label => $val): ?>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <span><?= htmlspecialchars($label) ?></span>
                    <strong><?= $val ?></strong>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="panel">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <h2 class="h5 mb-0 align-self-center">Riwayat Laporan</h2>
            <div class="d-flex gap-2 flex-wrap">
                <!-- Filter status cepat -->
                <a class="btn btn-sm <?= $filterStatus === '' ? 'btn-success' : 'btn-outline-secondary' ?>" href="laporan-saya.php">Semua</a>
                <?php foreach ($allowedStatuses as $s): ?>
                    <a class="btn btn-sm <?= $filterStatus === $s ? 'btn-success' : 'btn-outline-secondary' ?>"
                       href="laporan-saya.php?status=<?= urlencode($s) ?>"><?= $s ?></a>
                <?php endforeach; ?>
                <a class="btn btn-success btn-sm ms-2" href="/tikaclean/#lapor">+ Laporan Baru</a>
            </div>
        </div>

        <?php if ($reports): ?>
            <div class="row g-3">
                <?php foreach ($reports as $r): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="laporan-card">
                            <?php if ($r['photo']): ?>
                                <img src="/tikaclean/uploads/<?= htmlspecialchars($r['photo']) ?>"
                                     alt="Foto laporan #<?= $r['id'] ?>" class="laporan-card-img">
                            <?php endif; ?>
                            <div class="laporan-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="status-badge status-<?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span>
                                    <span class="text-muted" style="font-size:0.82rem;">#<?= $r['id'] ?></span>
                                </div>
                                <strong class="d-block mb-1"><?= htmlspecialchars($r['category']) ?></strong>
                                <p class="text-muted mb-2" style="font-size:0.88rem; line-height:1.5;">
                                    <?= htmlspecialchars(mb_strimwidth($r['description'], 0, 90, '...')) ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted" style="font-size:0.82rem;"><?= htmlspecialchars(substr($r['created_at'], 0, 10)) ?></span>
                                    <a class="btn btn-sm btn-outline-success" href="/tikaclean/tracking.php?id=<?= $r['id'] ?>">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted mb-3">
                    <?= $filterStatus ? 'Tidak ada laporan dengan status <strong>' . htmlspecialchars($filterStatus) . '</strong>.' : 'Anda belum pernah mengirim laporan.' ?>
                </p>
                <?php if (!$filterStatus): ?>
                    <a class="btn btn-success" href="/tikaclean/#lapor">Buat Laporan Pertama</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.laporan-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(15,35,29,0.06);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.laporan-card-img {
    width: 100%;
    height: 160px;
    object-fit: cover;
}
.laporan-card-body {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
</style>

<?php render_footer(); ?>
