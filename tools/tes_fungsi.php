<?php
// DRIVER TEST: memanggil fungsi yang diuji dengan data tertentu,
// lalu membandingkan hasil aktual dengan hasil yang diharapkan.
require __DIR__ . '/../includes/functions.php';
date_default_timezone_set('Asia/Jakarta');

// STUB: pengganti class Job asli. Tidak terhubung ke database,
// hanya mengembalikan data tetap, supaya pengujian tidak bergantung pada MySQL.
class JobStub
{
    public function find(int $id): ?array
    {
        $data = [1 => ['id' => 1, 'posisi' => 'Web Developer', 'gaji' => 9000000]];
        return $data[$id] ?? null;
    }
}

$stub = new JobStub();

// Setiap kasus: [nama uji, hasil aktual, hasil yang diharapkan]
$kasus = [
    ['formatRupiah(7500000)',         formatRupiah(7500000),        'Rp 7.500.000'],
    ['formatRupiah(0)',               formatRupiah(0),              'Rp 0'],
    ['e() mengamankan <script>',      e('<script>'),                '&lt;script&gt;'],
    ['e() menerima null',             e(null),                      ''],
    ['warnaStatus(Diterima)',         warnaStatus('Diterima'),      'bg-success'],
    ['warnaStatus(status lain)',      warnaStatus('Lainnya'),       'bg-secondary'],
    ['formatUkuran(500)',             formatUkuran(500),            '500 B'],
    ['formatUkuran(2048)',            formatUkuran(2048),           '2,0 KB'],
    ['formatUkuran(2621440)',         formatUkuran(2621440),        '2,50 MB'],
    ['ukuranFolder(folder tidak ada)', ukuranFolder('/tidak/ada'),  0],
    ['Stub: gaji lowongan id 1',      formatRupiah($stub->find(1)['gaji']), 'Rp 9.000.000'],
    ['Stub: lowongan id 99',          $stub->find(99),              null],
];

$lulus = 0;
$baris = '';
foreach ($kasus as [$nama, $aktual, $harapan]) {
    $ok = ($aktual === $harapan);
    if ($ok) {
        $lulus++;
    }
    $baris .= '<tr><td>' . e($nama) . '</td><td>' . e(var_export($harapan, true)) . '</td><td>'
            . e(var_export($aktual, true)) . '</td><td>' . ($ok ? 'LULUS' : 'GAGAL') . '</td></tr>';
}
$ringkasan = "$lulus dari " . count($kasus) . " kasus lulus";

// Simpan ringkasan ke file sebagai catatan hasil pengujian
file_put_contents(__DIR__ . '/hasil_tes_fungsi.txt', date('Y-m-d H:i:s') . ' - ' . $ringkasan . PHP_EOL, FILE_APPEND);
?>
<h1>Driver Test: includes/functions.php</h1>
<table border="1" cellpadding="6">
    <tr><th>Uji</th><th>Diharapkan</th><th>Aktual</th><th>Hasil</th></tr>
    <?= $baris ?>
</table>
<p><strong><?= $ringkasan ?></strong></p>