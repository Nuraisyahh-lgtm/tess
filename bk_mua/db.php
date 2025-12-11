<?php
$koneksi = mysqli_connect("localhost", "root", "", "booking_mua");
if (!$koneksi) {
    die("Gagal konek database: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
