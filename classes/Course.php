<?php
namespace App;

class Course
{
    // Daftar kategori resmi (dipakai untuk chip filter dan whitelist)
    const KATEGORI = [
        'Desain & Kreatif',
        'Pemasaran Digital',
        'Penulisan & Konten',
        'Bisnis & Administrasi',
        'Teknologi & Web',
    ];

    private \mysqli $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getConnection();
    }

    // Cari kursus berdasarkan kata kunci, dan (opsional) filter kategori
    public function search(string $keyword = '', string $kategori = ''): array
    {
        $like = '%' . $keyword . '%';

        if ($kategori !== '' && in_array($kategori, self::KATEGORI, true)) {
            $stmt = $this->conn->prepare(
                "SELECT * FROM courses WHERE judul LIKE ? AND kategori = ? ORDER BY rating DESC"
            );
            $stmt->bind_param("ss", $like, $kategori);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT * FROM courses WHERE judul LIKE ? ORDER BY rating DESC"
            );
            $stmt->bind_param("s", $like);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Ambil satu kursus berdasarkan id
    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}