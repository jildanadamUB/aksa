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

/**
 * Menghitung total ukuran semua file di dalam sebuah folder.
 *
 * @param string $folder  lokasi folder
 * @return int            total ukuran dalam byte
 */
function ukuranFolder(string $folder): int
{
    if (!is_dir($folder)) {
        return 0;
    }
    $total = 0;
    foreach (glob($folder . '/*') as $file) {
        if (is_file($file)) {
            $total += filesize($file);
        }
    }
    return $total;
}

/**
 * Mengubah ukuran byte menjadi teks yang mudah dibaca.
 * Contoh: 2500000 menjadi "2,38 MB"
 *
 * @param int $byte  ukuran dalam byte
 * @return string    ukuran dalam B, KB, atau MB
 */
function formatUkuran(int $byte): string
{
    if ($byte >= 1048576) {
        return number_format($byte / 1048576, 2, ',', '.') . ' MB';
    }
    if ($byte >= 1024) {
        return number_format($byte / 1024, 1, ',', '.') . ' KB';
    }
    return $byte . ' B';
}