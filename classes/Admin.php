<?php
namespace App;

// Admin adalah turunan User: mewarisi semua property & method milik User
class Admin extends User
{
    // Override: admin diarahkan ke dashboard admin
    public function dashboardUrl(): string
    {
        return '/aksa/admin/index.php';
    }

    // Method tambahan yang hanya dimiliki Admin
    public function countUsers(): int
    {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM users");
        return (int) $result->fetch_assoc()['total'];
    }
}