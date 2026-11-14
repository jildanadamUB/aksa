<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: /aksa/auth/login.php');
    exit;
}