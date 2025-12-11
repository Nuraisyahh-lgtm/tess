<?php
require 'cek_login.php';
if ($_SESSION['role'] !== 'customer') {
    die("Akses customer saja.");
}
?>
