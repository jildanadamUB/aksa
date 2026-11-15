<?php

require __DIR__ . '/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: /aksa/index.php');
    exit;
}