<?php
require_once __DIR__ . '/../app/auth.php';
require_admin();
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/layout.php';

$pdo = get_db();
$allowedStatuses = ['Menunggu', 'Diproses', 'Selesai'];

// Update status
$successMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = (int)($_POST['report_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? '');

    if ($reportId > 0 && in_array($newStatus, $allowedStatuses, true)) {
        $stmt = $pdo->prepare('UPDATE reports SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $reportId]);
        header('Location: index.php?updated=' . $reportId);
        exit;
    }
}

$updatedId = isset($_GET['updated']) ? (int)$_GET['updated'] : 0;

// Filter
$filterStatus = $_GET['status'] ?? '';
$filterSearch = trim($_GET['search'] ?? '');

$where = [];
$params = [];
if ($filterStatus && in_array($filterStatus, $allowedStatuses, true)) {
    $where[] = 'status = ?';
    $params[] = $filterStatus;
}
if ($filterSearch !== '') {
    $where[] = '(name LIKE ? OR category LIKE ?)';
    $params[] = '%' . $filterSearch . '%';
    $params[] = '%' . $filterSearch . '%';
}
$sql = 'SELECT * FROM reports' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stat cards — selalu dari semua laporan
$allReports = $pdo->query('SELECT status, created_at FROM reports ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
$total = count($allReports);
$statuses = array_fill_keys($allowedStatuses, 0);
$chartData = [];
$statusChartData = array_fill_keys($allowedStatuses, 0);
foreach ($allReports as $r) {
    if (isset($statuses[$r['status']])) {
        $statuses[$r['status']]++;
        $statusChartData[$r['status']]++;
    }
    $date = substr($r['created_at'], 0, 10);
    $chartData[$date] = ($chartData[$date] ?? 0) + 1;
}
ksort($chartData);

render_header('Dashboard Admin', 'dashboard', 'admin');
?>

<main class="container py-5 admin-page">
    <?php render_back_button('/tikaclean/', 'Kembali ke Halaman User'); ?>
    <div class="page-heading">
        <div class="section-kicker">Area Admin</div>
        <h1>Dashboard Pengelolaan Laporan</h1>
        <p class="text-muted">Pantau laporan masuk, perbarui status penanganan, dan lihat tren harian untuk tindakan yang lebih cepat.</p>
    </div>

    <?php if ($updatedId): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Status laporan <strong>#<?= $updatedId ?></strong> berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- Stat Cards -->
    <section class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <span>Total Laporan</span>
                <strong><?= $total ?></strong>
            </div>
        </div>
        <?php foreach ($statuses as $label => $value): ?>
            <div class="col-6 col-md-3">
                <div class="stat-card stat-<?= strtolower($label) ?>">
                    <span><?= htmlspecialchars($label) ?></span>
                    <strong><?= $value ?></strong>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Grafik -->
    <section class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="section-kicker">Tren Laporan</div>
                <h2 class="h5 mb-3">Jumlah laporan per hari</h2>
                <canvas id="reportChart" height="130"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel h-100">
                <div class="section-kicker">Distribusi Status</div>
                <h2 class="h5 mb-3">Proporsi tiap status</h2>
                <canvas id="statusChart" height="180"></canvas>
                <div class="d-flex flex-wrap gap-2 mt-3 justify-content-center">
                    <span class="status-badge status-menunggu">Menunggu: <?= $statuses['Menunggu'] ?></span>
                    <span class="status-badge status-diproses">Diproses: <?= $statuses['Diproses'] ?></span>
                    <span class="status-badge status-selesai">Selesai: <?= $statuses['Selesai'] ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabel Laporan -->
    <section class="panel">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <div>
                <div class="section-kicker">Tindak Lanjut</div>
                <h2 class="h4 mb-0">Laporan yang perlu dipantau</h2>
            </div>
            <a class="btn btn-outline-success align-self-md-center" href="/tikaclean/">Buka Halaman Warga</a>
        </div>

        <!-- Filter -->
        <form method="get" action="index.php" class="row g-2 mb-3 align-items-end">
            <div class="col-sm-5">
                <label class="form-label small fw-700 mb-1">Cari nama / kategori</label>
                <input class="form-control form-control-sm" type="text" name="search" value="<?= htmlspecialchars($filterSearch) ?>" placeholder="Ketik nama atau kategori...">
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-700 mb-1">Filter status</label>
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
                    <a class="btn btn-outline-secondary btn-sm" href="index.php">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($filterStatus || $filterSearch): ?>
            <p class="text-muted small mb-2">
                Menampilkan <strong><?= count($reports) ?></strong> laporan
                <?= $filterStatus ? 'dengan status <strong>' . htmlspecialchars($filterStatus) . '</strong>' : '' ?>
                <?= $filterSearch ? 'yang cocok dengan "<strong>' . htmlspecialchars($filterSearch) . '</strong>"' : '' ?>
            </p>
        <?php endif; ?>

        <?php if ($reports): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr <?= $report['id'] === $updatedId ? 'class="table-success"' : '' ?>>
                                <td>#<?= $report['id'] ?></td>
                                <td><?= htmlspecialchars($report['name']) ?></td>
                                <td><?= htmlspecialchars($report['category']) ?></td>
                                <td class="text-muted" style="max-width:220px; font-size:0.88rem;">
                                    <?= htmlspecialchars(mb_strimwidth($report['description'], 0, 80, '...')) ?>
                                </td>
                                <td>
                                    <form class="status-form" action="index.php" method="post">
                                        <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                        <select class="form-select form-select-sm" name="status" aria-label="Status laporan #<?= $report['id'] ?>">
                                            <?php foreach ($allowedStatuses as $statusOption): ?>
                                                <option value="<?= htmlspecialchars($statusOption) ?>" <?= $report['status'] === $statusOption ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($statusOption) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-sm btn-success" type="submit">Simpan</button>
                                    </form>
                                </td>
                                <td style="white-space:nowrap; font-size:0.88rem;"><?= htmlspecialchars(substr($report['created_at'], 0, 10)) ?></td>
                                <td><a class="btn btn-sm btn-outline-success" href="/tikaclean/tracking.php?id=<?= $report['id'] ?>">Lihat</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">
                <?= ($filterStatus || $filterSearch) ? 'Tidak ada laporan yang cocok dengan filter.' : 'Belum ada laporan yang masuk.' ?>
            </p>
        <?php endif; ?>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik tren harian
    new Chart(document.getElementById('reportChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_keys($chartData)) ?>,
            datasets: [{
                label: 'Laporan',
                data: <?= json_encode(array_values($chartData)) ?>,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.12)',
                pointBackgroundColor: '#0f766e',
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Grafik donut distribusi status
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($statusChartData)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($statusChartData)) ?>,
                backgroundColor: ['#dcfce7', '#dbeafe', '#bbf7d0'],
                borderColor: ['#16a34a', '#1d4ed8', '#047857'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
<?php render_footer('admin'); ?>
