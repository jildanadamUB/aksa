<?php
namespace App;

class User
{
    // protected: nanti bisa dipakai juga oleh class turunan (Admin)
    protected \mysqli $conn;

    // Object Database "disuntikkan" dari luar lewat constructor
    public function __construct(Database $db)
    {
        $this->conn = $db->getConnection();
    }

    // Cek apakah email sudah terdaftar
    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Menyimpan user baru dengan password yang sudah di-hash
    public function register(string $nama, string $email, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $email, $hash);
        return $stmt->execute();
    }

    // Mengembalikan data user kalau email & password benar, atau null kalau salah
    public function login(string $email, string $password): ?array
    {
        $stmt = $this->conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();

        if ($data && password_verify($password, $data['password'])) {
            return $data;
        }
        return null;
    }
    // Halaman tujuan setelah login (di-override oleh class Admin)
    public function dashboardUrl(): string
    {
        return '/aksa/index.php';
    }
}