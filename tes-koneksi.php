<?php
require 'config/init.php';

$koneksi = $db->getConnection();
echo "Koneksi berhasil! Versi MySQL: " . $koneksi->server_info;