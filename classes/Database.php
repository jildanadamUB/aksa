<?php
namespace App;
class Database
{
    // Property: data milik object (private = hanya bisa diakses dari dalam class ini)
    private string $host = "localhost";
    private string $user = "root";
    private string $pass = "";
    private string $dbName = "aksa";
    private \mysqli $conn;

    // Constructor: otomatis dijalankan saat "new Database()"
    public function __construct()
    {
        try {
            $this->conn = new \mysqli($this->host, $this->user, $this->pass, $this->dbName);
        } catch (\mysqli_sql_exception $e) {
            throw new \RuntimeException("Koneksi database gagal: " . $e->getMessage());
        }
    }

    // Method: memberikan koneksi ke kode di luar class
    public function getConnection(): \mysqli
    {
        return $this->conn;
    }
}