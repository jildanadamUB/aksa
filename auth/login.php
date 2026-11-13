<?php $judul = "Login"; include '../includes/header.php'; ?>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 p-4">
                    <h1 class="h3 fw-bold text-center mb-1">Masuk</h1>
                    <p class="text-center text-muted mb-4">Selamat datang di Aksa</p>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" required>
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