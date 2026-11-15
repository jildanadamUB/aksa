<?php
require '../config/init.php';
require '../includes/auth.php';

$judul    = "Riwayat Lamaran";
$appModel = new App\Application($db);
$riwayat  = $appModel->history($_SESSION['user_id']);
$total    = $appModel->countByUser($_SESSION['user_id']);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="col-12 col-lg-8 mx-auto">
        <a href="index.php" class="text-decoration-none">&larr; Kembali</a>

        <h1 class="h4 fw-bold mt-3 mb-1">Riwayat Lamaran</h1>
        <p class="text-muted small">Total lamaran: <?= $total ?></p>

        <?php if (empty($riwayat)) : ?>
            <div class="alert alert-info">Kamu belum melamar pekerjaan apa pun.</div>
        <?php endif; ?>

        <?php foreach ($riwayat as $r) : ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="h6 fw-bold mb-1"><?= e($r['posisi']) ?></h2>
                            <p class="small text-muted mb-1"><?= e($r['perusahaan']) ?> • <?= e($r['kota']) ?></p>
                            <p class="small text-muted mb-2">Dilamar <?= date('d M Y', strtotime($r['created_at'])) ?></p>
                            <a href="detail.php?id=<?= (int) $r['job_id'] ?>" class="small">Lihat lowongan</a>
                            <?php if ($r['cv']) : ?>
                                <a href="/aksa/uploads/cv/<?= e($r['cv']) ?>" class="small ms-2" target="_blank">Lihat CV</a>
                            <?php endif; ?>
                        </div>
                        <span class="badge <?= warnaStatus($r['status']) ?>"><?= e($r['status']) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>