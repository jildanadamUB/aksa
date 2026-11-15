<?php
/**
 * Mengubah angka menjadi format rupiah.
 * Contoh: formatRupiah(7500000) menghasilkan "Rp 7.500.000"
 *
 * @param int $angka  nominal dalam rupiah
 * @return string     teks rupiah
 */
function formatRupiah(int $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Mengamankan teks sebelum ditampilkan di HTML (mencegah XSS).
 *
 * @param string|null $teks  teks yang akan ditampilkan
 * @return string            teks yang sudah aman
 */
function e(?string $teks): string
{
    return htmlspecialchars($teks ?? '');
}

/**
 * Menentukan class warna badge Bootstrap sesuai status lamaran.
 *
 * @param string $status  status lamaran (Terkirim/Ditinjau/Diterima/Ditolak)
 * @return string         class Bootstrap untuk badge
 */
function warnaStatus(string $status): string
{
    return match ($status) {
        'Diterima' => 'bg-success',
        'Ditolak'  => 'bg-danger',
        'Ditinjau' => 'bg-warning text-dark',
        default    => 'bg-secondary',
    };
}

/**
 * Mencatat pesan error ke file logs/error.log beserta waktunya.
 * Ini PROSEDUR: melakukan sesuatu, tidak mengembalikan nilai (void).
 *
 * @param string $pesan  isi pesan error
 * @return void
 */
function catatError(string $pesan): void
{
    $folder = __DIR__ . '/../logs';
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
    $baris = '[' . date('Y-m-d H:i:s') . '] ' . $pesan . PHP_EOL;
    file_put_contents($folder . '/error.log', $baris, FILE_APPEND);
}