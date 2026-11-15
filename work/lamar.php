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
} else {
    $uploader = new App\CvUploader();
    $cv = $uploader->upload($_FILES['cv'] ?? ['error' => UPLOAD_ERR_NO_FILE], $userId);

    if ($cv === null) {
        $_SESSION['pesan'] = $uploader->getError();
    } elseif ($appModel->apply($userId, $jobId, $cv)) {
        $_SESSION['pesan'] = "Berhasil! Lamaranmu sedang ditinjau oleh tim perekrut.";
    } else {
        $_SESSION['pesan'] = "Lamaran gagal dikirim. Silakan coba lagi.";
    }
}

header('Location: detail.php?id=' . $jobId);
exit;