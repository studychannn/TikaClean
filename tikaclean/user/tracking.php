<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/layout.php';

$pdo = get_db();

// Detail laporan spesifik
$reportId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$report   = null;
if ($reportId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM reports WHERE id = ?');
    $stmt->execute([$reportId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// #5 Filter & cari di tabel
$filterStatus = $_GET['status'] ?? '';
$filterSearch = trim($_GET['search'] ?? '');
$allowedStatuses = ['Menunggu', 'Diproses', 'Selesai'];

$where  = [];
$params = [];

// #3 — kalau login, default tampilkan laporan sendiri; bisa toggle lihat semua
$showMine = is_user_logged_in() && !isset($_GET['semua']);
if ($showMine) {
    $where[]  = 'user_id = ?';
    $params[] = (int)$_SESSION['user_id'];
}
if ($filterStatus && in_array($filterStatus, $allowedStatuses, true)) {
    $where[]  = 'status = ?';
    $params[] = $filterStatus;
}
if ($filterSearch !== '') {
    $where[]  = '(category LIKE ? OR name LIKE ?)';
    $params[] = '%' . $filterSearch . '%';
    $params[] = '%' . $filterSearch . '%';
}

$sql        = 'SELECT id, name, category, status, created_at FROM reports'
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
            . ' ORDER BY created_at DESC';
$stmt       = $pdo->prepare($sql);
$stmt->execute($params);
$allReports = $stmt->fetchAll(PDO::FETCH_ASSOC);

render_header('Tracking Pekerjaan', 'tracking', 'user');
?>

<main class="container py-5">
    <?php render_back_button('/tikaclean/', 'Kembali ke Beranda'); ?>
    <div class="page-heading">
        <div class="section-kicker">Tracking Laporan</div>
        <h1>Status Laporan Sampah</h1>
        <p class="text-muted">Pantau perkembangan laporan yang sudah dikirim. Status diperbarui oleh petugas secara berkala.</p>
    </div>

    <div class="alert alert-info d-flex gap-2 align-items-start mb-4" role="note">
        <span style="font-size:1.1rem;">💡</span>
        <div style="font-size:0.93rem;">
            <strong>Cara mencari laporan Anda:</strong> Gunakan filter atau cari di tabel bawah, lalu klik <em>Detail</em> untuk melihat status lengkapnya.
            ID laporan ditampilkan setelah Anda berhasil mengirim laporan dari halaman beranda.
        </div>
    </div>

    <?php if ($report): ?>
        <section class="panel mb-4">
            <div class="row g-4 align-items-start">
                <div class="col-lg-7">
                    <span class="status-badge status-<?= strtolower($report['status']) ?>"><?= htmlspecialchars($report['status']) ?></span>
                    <h2 class="h3 mt-3">Rincian Laporan #<?= $report['id'] ?></h2>
                    <div class="detail-grid mt-3">
                        <div><span>Nama</span><strong><?= htmlspecialchars($report['name']) ?></strong></div>
                        <div><span>Kategori</span><strong><?= htmlspecialchars($report['category']) ?></strong></div>
                        <div><span>Lokasi GPS</span><strong><?= htmlspecialchars($report['latitude']) ?>, <?= htmlspecialchars($report['longitude']) ?></strong></div>
                        <div><span>Tanggal</span><strong><?= htmlspecialchars(substr($report['created_at'], 0, 10)) ?></strong></div>
                    </div>
                    <p class="mt-4 mb-0"><?= nl2br(htmlspecialchars($report['description'])) ?></p>
                </div>
                <div class="col-lg-5">
                    <?php if ($report['photo']): ?>
                        <img class="report-photo"
                             src="/tikaclean/uploads/<?= htmlspecialchars($report['photo']) ?>"
                             alt="Foto laporan #<?= $report['id'] ?>">
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="panel">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <div>
                <div class="section-kicker">Daftar Laporan</div>
                <h2 class="h4 mb-0">
                    <?= $showMine ? 'Laporan saya' : 'Semua laporan warga' ?>
                </h2>
            </div>
            <div class="d-flex gap-2 align-self-md-center flex-wrap">
                <?php if (is_user_logged_in()): ?>
                    <?php if ($showMine): ?>
                        <a class="btn btn-sm btn-outline-secondary" href="?semua=1">Lihat semua laporan</a>
                    <?php else: ?>
                        <a class="btn btn-sm btn-outline-success" href="tracking.php">Laporan saya saja</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a class="btn btn-success btn-sm" href="/tikaclean/#lapor">Buat Laporan Baru</a>
            </div>
        </div>

        <!-- #5 Filter -->
        <form method="get" action="tracking.php" class="row g-2 mb-3 align-items-end">
            <?php if (!$showMine): ?>
                <input type="hidden" name="semua" value="1">
            <?php endif; ?>
            <div class="col-sm-5">
                <label class="form-label small fw-bold mb-1">Cari kategori / nama</label>
                <input class="form-control form-control-sm" type="text" name="search"
                    value="<?= htmlspecialchars($filterSearch) ?>" placeholder="Ketik kategori atau nama...">
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-bold mb-1">Filter status</label>
                <select class="form-select form-select-sm" name="status">
                    <option value="">Semua Status</option>
                    <?php foreach ($allowedStatuses as $s): ?>
                        <option value="<?= $s ?>" <?= $filterStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-3 d-flex gap-2">
                <button class="btn btn-success btn-sm w-100" type="submit">Terapkan</button>
                <?php if ($filterStatus || $filterSearch): ?>
                    <a class="btn btn-outline-secondary btn-sm" href="tracking.php<?= !$showMine ? '?semua=1' : '' ?>">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($filterStatus || $filterSearch): ?>
            <p class="text-muted small mb-2">
                Menampilkan <strong><?= count($allReports) ?></strong> laporan
                <?= $filterStatus ? 'dengan status <strong>' . htmlspecialchars($filterStatus) . '</strong>' : '' ?>
                <?= $filterSearch ? 'yang cocok dengan "<strong>' . htmlspecialchars($filterSearch) . '</strong>"' : '' ?>
            </p>
        <?php endif; ?>

        <?php if ($allReports): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <?= !$showMine ? '<th>Nama</th>' : '' ?>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allReports as $item): ?>
                            <tr <?= $item['id'] === $reportId ? 'class="table-success"' : '' ?>>
                                <td>#<?= $item['id'] ?></td>
                                <td><?= htmlspecialchars($item['category']) ?></td>
                                <?= !$showMine ? '<td>' . htmlspecialchars($item['name']) . '</td>' : '' ?>
                                <td><span class="status-badge status-<?= strtolower($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span></td>
                                <td style="font-size:0.88rem; white-space:nowrap;"><?= htmlspecialchars(substr($item['created_at'], 0, 10)) ?></td>
                                <td><a class="btn btn-sm btn-outline-success" href="/tikaclean/tracking.php?id=<?= $item['id'] ?>">Detail</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">
                <?= ($filterStatus || $filterSearch)
                    ? 'Tidak ada laporan yang cocok dengan filter.'
                    : ($showMine ? 'Anda belum memiliki laporan. <a href="/tikaclean/#lapor">Buat laporan sekarang</a>.' : 'Belum ada laporan masuk.') ?>
            </p>
        <?php endif; ?>
    </section>
</main>

<?php render_footer(); ?>
