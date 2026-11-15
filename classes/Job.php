<?php
namespace App;

class Job implements CrudInterface
{
    private \mysqli $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getConnection();
    }

    // Ambil semua lowongan, yang terbaru di atas
    public function all(): array
    {
        $result = $this->conn->query("SELECT * FROM jobs ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
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

    // Hapus lowongan: BUAT SENDIRI
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}