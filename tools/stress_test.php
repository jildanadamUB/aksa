<?php
// STRESS TEST: mengukur waktu pencarian lowongan saat jumlah data diperbanyak.
// Data uji dimasukkan di dalam TRANSAKSI lalu di-ROLLBACK, jadi database asli tidak berubah.
require __DIR__ . '/../config/init.php';
require __DIR__ . '/../includes/admin_auth.php';   // hanya admin yang boleh menjalankan

$conn     = $db->getConnection();
$jobModel = new App\Job($db);
$tahap    = [100, 1000, 5000];   // jumlah data uji yang ditambahkan bertahap
const BATAS_MS = 500;            // batas waktu yang dianggap wajar
$hasil    = [];

$conn->begin_transaction();
$stmt = $conn->prepare(
    "INSERT INTO jobs (posisi, perusahaan, kota, tipe, gaji, deskripsi, kualifikasi)
     VALUES (?, 'Perusahaan Uji', ?, 'Fulltime', ?, 'Data uji', 'Data uji')"
);

$sudah = 0;
foreach ($tahap as $target) {
    // Tambah data sampai jumlahnya mencapai target tahap ini
    for (; $sudah < $target; $sudah++) {
        $posisi = 'Posisi Uji ' . $sudah;
        $kota   = ($sudah % 2 === 0) ? 'Jakarta' : 'Bandung';
        $gaji   = 3000000 + ($sudah % 50) * 100000;
        $stmt->bind_param("ssi", $posisi, $kota, $gaji);
        $stmt->execute();
    }

    // Ukur waktu pencarian + pengurutan
    $mulai = microtime(true);
    $data  = $jobModel->search('jakarta', 'gaji_tinggi');
    $waktu = (microtime(true) - $mulai) * 1000;   // milidetik

    $hasil[] = [
        'tambahan'  => $target,
        'ditemukan' => count($data),
        'waktu'     => round($waktu, 2),
        'memori'    => formatUkuran(memory_get_peak_usage(true)),
    ];
}

$conn->rollback();   // batalkan semua data uji

// Simpan hasil ke file sebagai catatan pengujian
$catatan = date('Y-m-d H:i:s') . PHP_EOL;
foreach ($hasil as $h) {
    $catatan .= "+{$h['tambahan']} data: {$h['ditemukan']} hasil, {$h['waktu']} ms, memori {$h['memori']}" . PHP_EOL;
}
file_put_contents(__DIR__ . '/hasil_stress_test.txt', $catatan . PHP_EOL, FILE_APPEND);
?>
<h1>Stress Test: pencarian lowongan</h1>
<table border="1" cellpadding="6">
    <tr><th>Data uji tambahan</th><th>Hasil ditemukan</th><th>Waktu (ms)</th><th>Memori puncak</th><th>Status</th></tr>
    <?php foreach ($hasil as $h) : ?>
        <tr>
            <td><?= $h['tambahan'] ?></td>
            <td><?= $h['ditemukan'] ?></td>
            <td><?= $h['waktu'] ?></td>
            <td><?= $h['memori'] ?></td>
            <td><?= $h['waktu'] <= BATAS_MS ? 'Wajar' : 'Lambat' ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<p>Semua data uji dibatalkan dengan rollback.</p>