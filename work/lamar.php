<?php
require '../config/init.php';
require '../includes/auth.php';

// Halaman ini hanya menerima kiriman form (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$jobId    = (int) ($_POST['job_id'] ?? 0);
$userId   = $_SESSION['user_id'];
$appModel = new App\Application($db);

if ($appModel->hasApplied($userId, $jobId)) {
    $_SESSION['pesan'] = "Kamu sudah melamar lowongan ini.";
} elseif ($appModel->apply($userId, $jobId)) {
    $_SESSION['pesan'] = "Berhasil! Lamaranmu sedang ditinjau oleh tim perekrut.";
} else {
    $_SESSION['pesan'] = "Lamaran gagal dikirim. Silakan coba lagi.";
}

header('Location: detail.php?id=' . $jobId);
exit;