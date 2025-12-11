<?php
require_once 'cek_login.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Halaman khusus admin.");
}
?>
