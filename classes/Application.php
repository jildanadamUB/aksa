<?php
namespace App;

class Application
{
    private \mysqli $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getConnection();
    }

    // Cek apakah user sudah pernah melamar lowongan ini
    public function hasApplied(int $userId, int $jobId): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
        $stmt->bind_param("ii", $userId, $jobId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Simpan lamaran + tambah jumlah pelamar dalam SATU TRANSAKSI
    public function apply(int $userId, int $jobId, ?string $cv = null): bool
    {
        $this->conn->begin_transaction();
        try {
            // Langkah 1: simpan lamaran
            $stmt = $this->conn->prepare("INSERT INTO applications (user_id, job_id, cv) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $userId, $jobId, $cv);
            $stmt->execute();

            // Langkah 2: tambah jumlah pelamar di lowongan
            $stmt = $this->conn->prepare("UPDATE jobs SET jumlah_pelamar = jumlah_pelamar + 1 WHERE id = ?");
            $stmt->bind_param("i", $jobId);
            $stmt->execute();

            $this->conn->commit();   // semua berhasil → simpan permanen
            return true;
        } catch (\mysqli_sql_exception $e) {
            $this->conn->rollback(); // ada yang gagal → batalkan semuanya
            return false;
        }
    }
}