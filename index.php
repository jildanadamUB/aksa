<?php
require 'config/init.php';
require 'includes/auth.php';
$judul = "Home";
$nama = $_SESSION['nama'];

$jobModel    = new App\Job($db);
$courseModel = new App\Course($db);

$lowongan = array_slice($jobModel->search('', 'terbaru'), 0, 3);   // 3 lowongan terbaru
$courses  = array_slice($courseModel->search(), 0, 3);              // 3 kursus rating tertinggi
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="container py-4">
    <h1 class="h4">Hello, <span class="fw-bold"><?= e($nama) ?></span></h1>
    <p class="text-muted">Raih masa depan yang lebih baik!</p>
    <section class="banner-aksa rounded-4 p-4 mb-4 text-white">
        <div class="row align-items-center">
            <div class="col-8">
                <h2 class="h5 fw-bold mb-1">Buka Learning Path Lainnya</h2>
                <p class="mb-3">Rp 50.000/learning path</p>
                <a href="/aksa/learn/" class="btn btn-light btn-sm fw-semibold">Lihat Kursus</a>
            </div>
            <div class="col-4 text-end">
                <i class="bi bi-megaphone-fill display-4"></i>
            </div>
        </div>
    </section>
    <section class="row g-3 mb-4">
        <div class="col-6">
            <a href="/aksa/learn/" class="menu-cepat card border-0 shadow-sm p-3 text-center text-decoration-none">
                <i class="bi bi-book fs-2 text-aksa"></i>
                <span class="fw-semibold mt-2">AksaLearn</span>
            </a>
        </div>
        <div class="col-6">
            <a href="/aksa/work/" class="menu-cepat card border-0 shadow-sm p-3 text-center text-decoration-none">
                <i class="bi bi-briefcase fs-2 text-aksa"></i>
                <span class="fw-semibold mt-2">AksaWork</span>
            </a>
        </div>
    </section>
    <section class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Rekomendasi Course</h2>
            <a href="/aksa/learn/" class="small fw-semibold">Lihat semua</a>
        </div>

        <div class="row g-3">
            <?php foreach ($courses as $c) : ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-3 menu-cepat">
                    <span class="badge badge-aksa align-self-start mb-2"><?= e($c['kategori']) ?></span>
                    <h3 class="h6 fw-bold mb-1">
                        <a href="/aksa/learn/detail.php?id=<?= (int) $c['id'] ?>"
                        class="stretched-link text-reset text-decoration-none"><?= e($c['judul']) ?></a>
                    </h3>
                    <p class="small text-muted mb-0">
                        <i class="bi bi-star-fill text-warning"></i> <?= number_format($c['rating'], 1) ?> • <?= $c['jumlah_video'] ?> Video • <?= $c['jumlah_bacaan'] ?> Bacaan
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Rekomendasi Lowongan</h2>
            <a href="/aksa/work/" class="small fw-semibold">Lihat semua</a>
        </div>

        <div class="row g-3">
            <?php foreach ($lowongan as $l) : ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-lowongan border-0 shadow-sm h-100 p-3 menu-cepat">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h3 class="h6 fw-bold mb-1">
                            <a href="/aksa/work/detail.php?id=<?= (int) $l['id'] ?>"
                            class="stretched-link text-reset text-decoration-none"><?= e($l['posisi']) ?></a>
                        </h3>
                        <i class="bi bi-bookmark"></i>
                    </div>
                    <p class="small text-white-50 mb-2"><?= e($l['perusahaan']) ?> • <?= e($l['kota']) ?></p>
                    <span class="badge bg-white text-dark align-self-start mb-2"><?= e($l['tipe']) ?></span>
                    <p class="fw-bold mb-0"><?= formatRupiah($l['gaji']) ?>/bulan</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>