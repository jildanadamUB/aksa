<?php
require '../config/init.php';
require '../includes/auth.php';

$judul    = "AksaWork";
$jobModel = new App\Job($db);
$cari = trim($_GET['cari'] ?? '');
$urut = $_GET['urut'] ?? 'terbaru';
$lowongan = $jobModel->search($cari, $urut);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container py-4">
    <h1 class="h4 fw-bold">AksaWork</h1>
    <p class="text-muted">Temukan pekerjaan impianmu.</p>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-12 col-md-6">
            <input type="text" name="cari" class="form-control"
                placeholder="Cari posisi, perusahaan, atau kota" value="<?= e($cari) ?>">
        </div>
        <div class="col-8 col-md-4">
            <select name="urut" class="form-select">
                <option value="terbaru" <?= $urut === 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
                <option value="gaji_tinggi" <?= $urut === 'gaji_tinggi' ? 'selected' : '' ?>>Gaji tertinggi</option>
                <option value="gaji_rendah" <?= $urut === 'gaji_rendah' ? 'selected' : '' ?>>Gaji terendah</option>
            </select>
        </div>
        <div class="col-4 col-md-2">
            <button type="submit" class="btn btn-aksa w-100">Cari</button>
        </div>
    </form>

    <?php if (empty($lowongan)) : ?>
        <div class="alert alert-warning">Lowongan tidak ditemukan.</div>
    <?php endif; ?>

    <div class="row g-3">
        <?php foreach ($lowongan as $l) : ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <span class="badge badge-aksa align-self-start mb-2"><?= e($l['tipe']) ?></span>
                        <h5 class="fw-bold"><?= e($l['posisi']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= e($l['perusahaan']) ?> • <?= e($l['kota']) ?></h6>
                        <p class="card-text mb-2"><?= e($l['deskripsi']) ?></p>
                        <p class="card-text mt-auto text-success"><strong>Gaji:</strong> <?= formatRupiah($l['gaji']) ?>/bulan</p>
                        <a href="detail.php?id=<?= $l['id'] ?>" class="btn btn-aksa mt-2">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>