<?php
// Zona waktu Indonesia (WIB), supaya waktu di log dan database sesuai
date_default_timezone_set('Asia/Jakarta');

// Mulai session (dipakai untuk menyimpan status login)
session_start();

// Fungsi-fungsi bantu: harus dimuat PALING AWAL, karena dipakai oleh penangan error di bawah
require __DIR__ . '/../includes/functions.php';

// Autoload: otomatis require file class saat class dipakai pertama kali
spl_autoload_register(function ($class) {
    $namaClass = str_replace('App\\', '', $class);
    require __DIR__ . '/../classes/' . $namaClass . '.php';
});

// Penangan error global: semua exception yang tidak tertangkap berakhir di sini
set_exception_handler(function (\Throwable $e) {
    catatError($e->getMessage() . ' (' . $e->getFile() . ' baris ' . $e->getLine() . ')');
    http_response_code(500);
    include __DIR__ . '/../includes/error_page.php';
    exit;
});

// Satu object Database dipakai bersama oleh semua halaman
$db = new App\Database();