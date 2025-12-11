<?php
require 'db.php';
require 'cek_admin.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM mua WHERE id=$id");

header("Location: admin_mua.php");
exit;
