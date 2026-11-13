<?php
// Mulai session (dipakai untuk menyimpan status login)
session_start();

// Autoload: otomatis require file class saat class dipakai pertama kali
spl_autoload_register(function ($class) {
    // "App\Database" → "Database" → classes/Database.php
    $namaClass = str_replace('App\\', '', $class);
    require __DIR__ . '/../classes/' . $namaClass . '.php';
});

// Satu object Database dipakai bersama oleh semua halaman
$db = new App\Database();