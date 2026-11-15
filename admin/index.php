<?php
require '../config/init.php';
require '../includes/admin_auth.php';

$judul = "Dashboard Admin";
$admin = new App\Admin($db);

include '../includes/header.php';
include '../includes/navbar.php';
?>
<main class="container py-4">
    <h1 class="h4 fw-bold">Dashboard Admin</h1>
    <p>Total pengguna terdaftar: <strong><?= $admin->countUsers() ?></strong></p>
</main>
<?php include '../includes/footer.php'; ?>