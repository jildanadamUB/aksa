<?php
namespace App;

class Job implements CrudInterface
{
    private \mysqli $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getConnection();
    }

    // Ambil semua lowongan, yang terbaru di atas.
    // Pengecualian guideline: memakai query() biasa (bukan prepared statement)
    // karena query ini tidak memuat input dari pengguna, jadi tidak ada risiko SQL Injection.
    public function all(): array
    {
        $result = $this->conn->query("SELECT * FROM jobs ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Cari lowongan berdasarkan kata kunci, lalu urutkan sesuai pilihan.
    // Parameter default membuat method ini bisa dipanggil dengan 0, 1, atau 2 argumen.
    public function search(string $keyword = '', string $urut = 'terbaru'): array
    {
        // Whitelist: ORDER BY tidak bisa memakai "?", jadi pilihannya dibatasi di sini
        $daftarUrut = [
            'terbaru'     => 'created_at DESC',
            'gaji_tinggi' => 'gaji DESC',
            'gaji_rendah' => 'gaji ASC',
        ];
        $orderBy = $daftarUrut[$urut] ?? $daftarUrut['terbaru'];

        $like = '%' . $keyword . '%';
        $stmt = $this->conn->prepare(
            "SELECT * FROM jobs
            WHERE posisi LIKE ? OR perusahaan LIKE ? OR kota LIKE ?
            ORDER BY $orderBy"
        );
        $stmt->bind_param("sss", $like, $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil satu lowongan berdasarkan id
    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tambah lowongan baru
    public function create(array $data): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO jobs (posisi, perusahaan, kota, tipe, gaji, deskripsi, kualifikasi)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssssiss",
            $data['posisi'], $data['perusahaan'], $data['kota'], $data['tipe'],
            $data['gaji'], $data['deskripsi'], $data['kualifikasi']
        );
        return $stmt->execute();
    }

    // Ubah lowongan
    public function update(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE jobs SET posisi = ?, perusahaan = ?, kota = ?, tipe = ?, gaji = ?,
             deskripsi = ?, kualifikasi = ? WHERE id = ?"
        );
        $stmt->bind_param(
            "ssssissi",
            $data['posisi'], $data['perusahaan'], $data['kota'], $data['tipe'],
            $data['gaji'], $data['deskripsi'], $data['kualifikasi'], $id
        );
        return $stmt->execute();
    }

    // Hapus lowongan berdasarkan id
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}