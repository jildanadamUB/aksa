<?php
require '../config/init.php';

header('Content-Type: application/json; charset=utf-8');

// Penangan error khusus API: jawab dengan JSON, bukan halaman HTML
set_exception_handler(function (\Throwable $e) {
    catatError($e->getMessage() . ' (' . $e->getFile() . ' baris ' . $e->getLine() . ')');
    http_response_code(500);   // Internal Server Error
    echo json_encode(['status' => 'error', 'pesan' => 'Terjadi kesalahan pada server']);
    exit;
});

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);   // Method Not Allowed
    echo json_encode(['status' => 'error', 'pesan' => 'Hanya metode GET yang diizinkan']);
    exit;
}

$jobModel = new App\Job($db);

if (isset($_GET['id'])) {
    $job = $jobModel->find((int) $_GET['id']);

    if (!$job) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'pesan' => 'Lowongan tidak ditemukan']);
        exit;
    }

    echo json_encode(['status' => 'ok', 'data' => $job], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$cari = trim($_GET['cari'] ?? '');
$urut = $_GET['urut'] ?? 'terbaru';
$data = $jobModel->search($cari, $urut);

echo json_encode([
    'status' => 'ok',
    'jumlah' => count($data),
    'data'   => $data,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);