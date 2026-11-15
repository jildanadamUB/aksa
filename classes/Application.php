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

    // Ambil riwayat lamaran seorang user lewat STORED PROCEDURE
    public function history(int $userId): array
    {
        $stmt = $this->conn->prepare("CALL sp_riwayat_lamaran(?)");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // CALL selalu mengirim hasil tambahan (status eksekusi). Hasil itu harus dihabiskan,
        // kalau tidak query berikutnya error "Commands out of sync".
        while ($stmt->more_results()) {
            $stmt->next_result();
        }
        $stmt->close();

        return $data;
    }

    // Hitung jumlah lamaran seorang user lewat FUNCTION SQL
    public function countByUser(int $userId): int
    {
        $stmt = $this->conn->prepare("SELECT fn_jumlah_lamaran(?) AS total");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }
}