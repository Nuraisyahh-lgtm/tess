<?php
require 'db.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header("Location: admin_mua.php");
} else {
    header("Location: home.php");
}
exit;
