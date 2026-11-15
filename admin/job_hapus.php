<?php
require '../config/init.php';
require '../includes/admin_auth.php';

// Halaman ini hanya menerima kiriman form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id    = (int) ($_POST['id'] ?? 0);
$jobModel = new App\Job($db);

if ($jobModel->delete($id)) {
    $_SESSION['pesan'] = "Berhasil! Lowongan berhasil dihapus.";
} else {
    $_SESSION['pesan'] = "Gagal menghapus lowongan. Silakan coba lagi.";
}

header('Location: index.php');
exit;