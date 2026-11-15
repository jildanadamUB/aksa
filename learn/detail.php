<?php
require '../config/init.php';
require '../includes/auth.php';

$courseModel = new App\Course($db);
$id     = (int) ($_GET['id'] ?? 0);
$course = $courseModel->find($id);

if (!$course) {
    http_response_code(404);
    $judul = "Tidak ditemukan";
    include '../includes/header.php';
    include '../includes/navbar.php';
    echo '<main class="container py-4"><div class="alert alert-warning">Kursus tidak ditemukan.</div>
          <a href="index.php">Kembali ke AksaLearn</a></main>';
    include '../includes/footer.php';
    exit;
}

$fileVideo = __DIR__ . '/../assets/video/' . ($course['video'] ?? '');
$adaVideo  = $course['video'] && file_exists($fileVideo);

$judul = $course['judul'];
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="col-12 col-lg-8 mx-auto">
        <a href="index.php" class="text-decoration-none">&larr; Kembali</a>

        <!-- Kartu judul -->
        <div class="card border-0 shadow-sm mt-3 mb-3">
            <div class="card-body">
                <span class="badge badge-aksa mb-2"><?= e($course['kategori']) ?></span>
                <h1 class="h3 fw-bold"><?= e($course['judul']) ?></h1>
                <p class="small text-muted mb-0">
                    <i class="bi bi-star-fill text-warning"></i> <?= number_format($course['rating'], 1) ?>
                    • <?= (int) $course['jumlah_video'] ?> Video • <?= (int) $course['jumlah_bacaan'] ?> Bacaan
                </p>
            </div>
        </div>

        <!-- Pemutar video -->
        <?php if ($adaVideo) : ?>
            <div class="ratio ratio-16x9 mb-3">
                <video controls preload="metadata" class="rounded-3 bg-dark">
                    <source src="/aksa/assets/video/<?= e($course['video']) ?>" type="video/mp4">
                    Browser kamu tidak mendukung pemutar video.
                </video>
            </div>
        <?php else : ?>
            <div class="alert alert-secondary mb-3">Video untuk kursus ini belum tersedia.</div>
        <?php endif; ?>

        <!-- Tentang Course -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h5 fw-bold">Tentang Course</h2>
                <p class="mb-0"><?= nl2br(e($course['deskripsi'])) ?></p>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>