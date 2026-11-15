<?php
require 'config/init.php';
require 'includes/auth.php';

$userModel = new App\User($db);
$appModel  = new App\Application($db);
$errors    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBaru = trim($_POST['nama'] ?? '');

    if ($namaBaru === '') {
        $errors[] = "Nama tidak boleh kosong.";
    } elseif (mb_strlen($namaBaru) > 100) {
        $errors[] = "Nama maksimal 100 karakter.";
    }

    if (empty($errors)) {
        $userModel->updateNama($_SESSION['user_id'], $namaBaru);
        $_SESSION['nama']  = $namaBaru;   // perbarui juga nama di session
        $_SESSION['pesan'] = "Profil berhasil diperbarui.";
        header('Location: profile.php');
        exit;
    }
}

$user    = $userModel->findById($_SESSION['user_id']);
$total   = $appModel->countByUser($_SESSION['user_id']);
$inisial = mb_strtoupper(mb_substr($user['nama'], 0, 1));

$judul = "Profil";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<main class="container py-4">
    <div class="col-12 col-lg-8 mx-auto">

        <section class="banner-aksa rounded-4 p-4 mb-4 text-white d-flex align-items-center gap-3">
            <div class="avatar"><?= e($inisial) ?></div>
            <div>
                <h1 class="h5 fw-bold mb-0"><?= e($user['nama']) ?></h1>
                <p class="mb-0 small"><?= e($user['email']) ?></p>
                <span class="badge bg-white text-dark mt-1"><?= e(ucfirst($user['role'])) ?></span>
            </div>
        </section>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= e($_SESSION['pesan']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Lamaran</h5>
                <p class="card-text"><?= $total ?></p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Edit Nama</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= e($user['nama']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-aksa">Simpan</button>
                </form>
            </div>
        </div>

        <div class="list-group shadow-sm">
            <a href="/aksa/work/riwayat.php" class="list-group-item list-group-item-action">Riwayat Lamaran</a>
            <a href="/aksa/auth/logout.php" class="list-group-item list-group-item-action text-danger">Keluar</a>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>