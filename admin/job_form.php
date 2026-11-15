<?php
require '../config/init.php';
require '../includes/admin_auth.php';

$jobModel = new App\Job($db);
$errors   = [];

// Mode edit kalau ada ?id= di URL, mode tambah kalau tidak ada
$id     = (int) ($_GET['id'] ?? 0);
$isEdit = $id > 0;

// Nilai awal form
$data = [
    'posisi' => '', 'perusahaan' => '', 'kota' => '', 'tipe' => 'Fulltime',
    'gaji' => '', 'deskripsi' => '', 'kualifikasi' => '',
];

if ($isEdit) {
    $job = $jobModel->find($id);
    if (!$job) {
        $_SESSION['pesan'] = "Lowongan tidak ditemukan.";
        header('Location: index.php');
        exit;
    }
    $data = $job;   // isi form dengan data lama
}

$daftarTipe = ['Fulltime', 'Parttime', 'Remote', 'Magang'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil semua isian form
    foreach (['posisi', 'perusahaan', 'kota', 'tipe', 'deskripsi', 'kualifikasi'] as $kolom) {
        $data[$kolom] = trim($_POST[$kolom] ?? '');
    }
    $data['gaji'] = (int) ($_POST['gaji'] ?? 0);

    // Validasi
    if ($data['posisi'] === '' || $data['perusahaan'] === '' || $data['kota'] === '') {
        $errors[] = "Posisi, perusahaan, dan kota wajib diisi.";
    }
    if (!in_array($data['tipe'], $daftarTipe, true)) {
        $errors[] = "Tipe pekerjaan tidak valid.";
    }
    if ($data['gaji'] <= 0) {
        $errors[] = "Gaji harus lebih dari 0.";
    }

    if (empty($errors)) {
        if ($isEdit) {
            $jobModel->update($id, $data);
            $_SESSION['pesan'] = "Lowongan berhasil diperbarui.";
        } else {
            $jobModel->create($data);
            $_SESSION['pesan'] = "Lowongan berhasil ditambahkan.";
        }
        header('Location: index.php');
        exit;
    }
}

$judul = $isEdit ? "Edit Lowongan" : "Tambah Lowongan";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container py-4">
    <div class="col-12 col-lg-8 mx-auto">
        <a href="index.php" class="text-decoration-none">&larr; Kembali</a>
        <h1 class="h4 fw-bold mt-3"><?= $judul ?></h1>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err) : ?>
                    <div><?= $err ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="card border-0 shadow-sm p-4">
            <div class="mb-3">
                <label for="posisi" class="form-label">Posisi</label>
                <input type="text" id="posisi" name="posisi" class="form-control"
                       value="<?= e($data['posisi']) ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="perusahaan" class="form-label">Perusahaan</label>
                    <input type="text" id="perusahaan" name="perusahaan" class="form-control"
                           value="<?= e($data['perusahaan']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kota" class="form-label">Kota</label>
                    <input type="text" id="kota" name="kota" class="form-control"
                           value="<?= e($data['kota']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipe" class="form-label">Tipe</label>
                    <select id="tipe" name="tipe" class="form-select">
                        <?php foreach ($daftarTipe as $t) : ?>
                            <option value="<?= $t ?>" <?= $data['tipe'] === $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gaji" class="form-label">Gaji per bulan (Rp)</label>
                    <input type="number" id="gaji" name="gaji" class="form-control" min="1"
                           value="<?= e((string) $data['gaji']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= e($data['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="kualifikasi" class="form-label">Kualifikasi (satu per baris)</label>
                <textarea id="kualifikasi" name="kualifikasi" class="form-control" rows="4"><?= e($data['kualifikasi']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-aksa">Simpan</button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>