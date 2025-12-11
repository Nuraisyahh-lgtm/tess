<?php
require 'db.php';
require 'cek_admin.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    mysqli_query($koneksi, "DELETE FROM mua WHERE id=$id");
}
header("Location: admin_mua.php");
exit;
