<?php
$judul = "Gangguan Sistem";
include __DIR__ . '/header.php';
?>
<main class="container min-vh-100 d-flex flex-column justify-content-center align-items-center text-center">
    <i class="bi bi-cone-striped display-1 text-aksa"></i>
    <h1 class="h4 fw-bold mt-3">Maaf, sistem sedang mengalami gangguan</h1>
    <p class="text-muted">Tim kami sudah mendapat catatan masalah ini. Silakan coba beberapa saat lagi.</p>
    <a href="/aksa/" class="btn btn-aksa">Coba Lagi</a>
</main>
<?php include __DIR__ . '/footer.php'; ?>