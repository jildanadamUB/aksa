<?php
require '../config/init.php';
require '../includes/admin_auth.php';

$judul = "Dashboard Admin";
$admin = new App\Admin($db);
$jobModel = new App\Job($db);
$jobs     = $jobModel->all();
$fileLog   = __DIR__ . '/../logs/error.log';
$errorLogs = file_exists($fileLog) ? array_slice(file($fileLog), -5) : [];

// Monitoring penyimpanan CV
const KUOTA_UPLOAD = 100 * 1024 * 1024;   // batas atas: 100 MB

$folderCv    = __DIR__ . '/../uploads/cv';
$pakaiUpload = ukuranFolder($folderCv);
$jumlahCv    = is_dir($folderCv) ? count(glob($folderCv . '/*')) : 0;
$persen      = min(100, round($pakaiUpload / KUOTA_UPLOAD * 100, 1));

$warnaBar = match (true) {
    $persen >= 80 => 'bg-danger',
    $persen >= 50 => 'bg-warning',
    default       => 'bg-success',
};

include '../includes/header.php';
include '../includes/navbar.php';
?>
<main class="container py-4">
    <h1 class="h4 fw-bold">Dashboard Admin</h1>

    <?php if (isset($_SESSION['pesan'])) : ?>
        <div class="alert alert-info"><?= e($_SESSION['pesan']) ?></div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <?php if (!empty($errorLogs)) : ?>
        <div class="alert alert-warning">
            <strong>⚠ Error terbaru</strong>
            <ul class="mb-0 mt-2 small">
                <?php foreach ($errorLogs as $baris) : ?>
                    <li><?= e($baris) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="small text-muted">Pengguna</div>
                <div class="h4 fw-bold mb-0"><?= $admin->countUsers() ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="small text-muted">Lowongan</div>
                <div class="h4 fw-bold mb-0"><?= count($jobs) ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="small text-muted">Lamaran</div>
                <div class="h4 fw-bold mb-0"><?= $admin->countApplications() ?></div>
            </div>
        </div>
        <div class="card border-0 shadow-sm p-3 mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">Penyimpanan CV</span>
                <span class="small text-muted"><?= $jumlahCv ?> file</span>
            </div>
            <div class="progress" style="height: 12px;">
                <div class="progress-bar <?= $warnaBar ?>" style="width: <?= $persen ?>%"></div>
            </div>
            <div class="small text-muted mt-2">
                <?= formatUkuran($pakaiUpload) ?> dari <?= formatUkuran(KUOTA_UPLOAD) ?> (<?= $persen ?>%)
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold mb-0">Kelola Lowongan</h2>
        <a href="job_form.php" class="btn btn-aksa btn-sm">+ Tambah Lowongan</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead>
                <tr>
                    <th>Posisi</th>
                    <th>Perusahaan</th>
                    <th>Kota</th>
                    <th>Gaji</th>
                    <th>Pelamar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $j) : ?>
                    <tr>
                        <td><?= e($j['posisi']) ?></td>
                        <td><?= e($j['perusahaan']) ?></td>
                        <td><?= e($j['kota']) ?></td>
                        <td><?= formatRupiah($j['gaji']) ?></td>
                        <td><?= (int) $j['jumlah_pelamar'] ?></td>
                        <td>
                            <a href="job_form.php?id=<?= (int) $j['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="job_hapus.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')">
                                <input type="hidden" name="id" value="<?= (int) $j['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include '../includes/footer.php'; ?>