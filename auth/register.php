<?php
require '../config/init.php';

$judul  = "Daftar Akun";
$errors = [];
$nama   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data form (trim = buang spasi di awal/akhir)
    $nama       = trim($_POST['nama']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $konfirmasi = $_POST['confirm_password'];

    $user = new App\User($db);

    // Validasi
    if ($nama === '' || $email === '' || $password === '') {
        $errors[] = "Semua kolom wajib diisi.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password minimal 8 karakter.";
    }
    if ($password !== $konfirmasi) {
        $errors[] = "Konfirmasi password tidak cocok.";
    }
    if ($user->emailExists($email)) {
        $errors[] = "Email sudah terdaftar.";
    }

    // Kalau tidak ada error: simpan, lalu pindah ke halaman login
    if (empty($errors)) {
        $user->register($nama, $email, $password);
        $_SESSION['pesan'] = "Akun berhasil dibuat. Silakan masuk.";
        header('Location: login.php');
        exit;
    }
}

include '../includes/header.php';
?>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 p-4">
                    <h1 class="h3 fw-bold text-center mb-1">Daftar Akun</h1>
                    <p class="text-center text-muted mb-4">Selamat datang di Aksa</p>
                    <?php if (!empty($errors)) : ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $err) : ?>
                                <div><?= $err ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="nama" value="<?= htmlspecialchars($nama) ?>" placeholder="John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="contoh@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-aksa w-100">Daftar</button>
                    </form>

                    <p class="text-center mt-3 mb-0">Sudah punya akun? <a href="login.php">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>