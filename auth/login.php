<?php
require '../config/init.php';

$judul  = "Login";
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data form (trim = buang spasi di awal/akhir)
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];

    $user = new App\User($db);
    $data = $user->login($email, $password);

    if ($data) {
        // Login berhasil: simpan data penting ke session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama']    = $data['nama'];
        $_SESSION['role']    = $data['role'];

        // Polymorphism: object dipilih sesuai role, lalu method yang SAMA dipanggil
        $akun = ($data['role'] === 'admin') ? new App\Admin($db) : new App\User($db);
        header('Location: ' . $akun->dashboardUrl());
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}

include '../includes/header.php';
?>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 p-4">
                    <h1 class="h3 fw-bold text-center mb-1">Masuk</h1>
                    <p class="text-center text-muted mb-4">Selamat datang di Aksa</p>
                    <?php if (isset($_SESSION['pesan'])) : ?>
                        <div class="alert alert-success"><?= $_SESSION['pesan'] ?></div>
                        <?php unset($_SESSION['pesan']); ?>
                    <?php endif; ?>

                    <?php if ($error !== '') : ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="contoh@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-aksa w-100">Masuk</button>
                    </form>

                    <p class="text-center mt-3 mb-0">Belum punya akun? <a href="register.php">Daftar</a></p>
                </div>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>