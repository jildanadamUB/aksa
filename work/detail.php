<?php
require '../config/init.php';
require '../includes/auth.php';

$jobModel = new App\Job($db);
$id  = (int) ($_GET['id'] ?? 0);
$job = $jobModel->find($id);

if (!$job) {
    http_response_code(404);
    $judul = "Tidak ditemukan";
    include '../includes/header.php';
    include '../includes/navbar.php';
    echo '<main class="container py-4"><div class="alert alert-warning">Lowongan tidak ditemukan.</div>
          <a href="index.php">Kembali ke AksaWork</a></main>';
    include '../includes/footer.php';
    exit;
}

$judul = $job['posisi'];
$kualifikasi = array_filter(array_map('trim', explode("\n", $job['kualifikasi'])), 'strlen');

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="col-12 col-lg-8 mx-auto">
        <a href="index.php" class="text-decoration-none">&larr; Kembali</a>

        <?php if (isset($_SESSION['pesan'])) : ?>
            <div class="alert alert-info mt-3 mb-0"><?= e($_SESSION['pesan']) ?></div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mt-3 mb-3">
            <div class="card-body">
                <span class="badge badge-aksa mb-2"><?= e($job['tipe']) ?></span>
                <h1 class="h3 fw-bold"><?= e($job['posisi']) ?></h1>
                <p class="text-muted mb-2"><?= e($job['perusahaan']) ?> • <?= e($job['kota']) ?></p>
                <p class="text-success fw-bold mb-1"><?= formatRupiah($job['gaji']) ?>/bulan</p>
                <p class="small text-muted mb-0"><?= (int) $job['jumlah_pelamar'] ?> pelamar</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h5 fw-bold">Deskripsi Pekerjaan</h2>
                <p class="mb-0"><?= nl2br(e($job['deskripsi'])) ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h5 fw-bold">Kualifikasi</h2>
                <ol class="mb-0">
                    <?php foreach ($kualifikasi as $k) : ?>
                        <li><?= e($k) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>

        <form method="POST" action="lamar.php">
            <input type="hidden" name="job_id" value="<?= (int) $job['id'] ?>">
            <button type="submit" class="btn btn-aksa w-100">Lamar Sekarang</button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
