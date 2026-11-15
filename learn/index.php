<?php
require '../config/init.php';
require '../includes/auth.php';

$judul       = "AksaLearn";
$courseModel = new App\Course($db);
$cari        = trim($_GET['cari'] ?? '');
$kategori    = $_GET['kategori'] ?? '';
$courses     = $courseModel->search($cari, $kategori);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 fw-bold mb-0">AksaLearn</h1>
    </div>

    <p class="text-muted">Tingkatkan keterampilanmu dengan kursus online.</p>

    <form method="GET" class="row g-2 mb-4">
        <!-- Chip filter kategori (link biasa, di luar form) -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="index.php" class="btn btn-sm rounded-pill <?= $kategori === '' ? 'btn-aksa' : 'btn-outline-secondary' ?>">Semua</a>
            <?php foreach (App\Course::KATEGORI as $k) : ?>
                <a href="?kategori=<?= urlencode($k) ?>"
                class="btn btn-sm rounded-pill <?= $kategori === $k ? 'btn-aksa' : 'btn-outline-secondary' ?>">
                    <?= e($k) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Form pencarian -->
        <form method="GET" class="row g-2 mb-4">
            <input type="hidden" name="kategori" value="<?= e($kategori) ?>">
            <div class="col-8 col-md-10">
                <input type="text" name="cari" class="form-control"
                    placeholder="Cari kursus" value="<?= e($cari) ?>">
            </div>
            <div class="col-4 col-md-2">
                <button type="submit" class="btn btn-aksa w-100">Cari</button>
            </div>
        </form>
    </form>
    <?php if (empty($courses)) : ?>
        <div class="alert alert-warning">Kursus tidak ditemukan.</div>
    <?php endif; ?>
    <div class="row g-3">
        <?php foreach ($courses as $course) : ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <span class="badge badge-aksa align-self-start mb-2"><?= e($course['kategori']) ?></span>
                        <h5 class="fw-bold"><?= e($course['judul']) ?></h5>
                        <p class="small text-muted mb-2">
                            <i class="bi bi-star-fill text-warning"></i> <?= number_format($course['rating'], 1) ?>
                            • <?= (int) $course['jumlah_video'] ?> Video • <?= (int) $course['jumlah_bacaan'] ?> Bacaan
                        </p>
                        <p class="card-text mb-2"><?= e($course['deskripsi']) ?></p>
                        <a href="detail.php?id=<?= (int) $course['id'] ?>" class="btn btn-aksa mt-auto">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
