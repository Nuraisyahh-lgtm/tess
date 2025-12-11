<?php
require 'db.php';

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
    $pass = mysqli_real_escape_string($koneksi, $_POST['password'] ?? '');

    if ($user === '' || $pass === '') {
        $error = "Username dan password wajib diisi.";
    } else {
        $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE username='$user' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah digunakan, coba yang lain.";
        } else {
            $q = mysqli_query(
                $koneksi,
                "INSERT INTO users(username,password,role) VALUES('$user','$pass','customer')"
            );
            if ($q) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Gagal mendaftar, coba beberapa saat lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar - Booking MUA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      background: radial-gradient(circle at top, #ffd6ff 0, #c4a7ff 40%, #b39ddb 70%, #b39ddb 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-wrapper {
      width: 100%;
      max-width: 420px;
      padding: 16px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.98);
      border-radius: 24px;
      padding: 26px 26px 24px;
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.16);
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: "";
      position: absolute;
      width: 180px;
      height: 180px;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.22), transparent 70%);
      top: -70px;
      right: -70px;
      z-index: -1;
    }

    .login-header {
      text-align: center;
      margin-bottom: 18px;
    }

    .login-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #fef3c7;
      color: #92400e;
      font-size: 11px;
      padding: 4px 10px;
      border-radius: 999px;
      margin-bottom: 8px;
    }

    .login-brand {
      font-weight: 700;
      font-size: 22px;
      color: #4c1d95;
      margin-bottom: 4px;
    }

    .login-title {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 2px;
    }

    .login-subtitle {
      font-size: 12px;
      color: #6b7280;
    }

    .alert-error {
      background: #fee2e2;
      color: #b91c1c;
      border-radius: 12px;
      padding: 8px 10px;
      font-size: 12px;
      margin-bottom: 12px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 14px;
    }

    .form-label {
      font-size: 12px;
      font-weight: 500;
      color: #4b5563;
      margin-bottom: 4px;
      display: block;
    }

    .input-shell {
      background: #f9fafb;
      border-radius: 12px;
      display: flex;
      align-items: center;
      padding: 0 10px;
      border: 1px solid transparent;
    }

    .input-shell:focus-within {
      border-color: #6366f1;
      background: #fff;
      box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.25);
    }

    .input-shell span {
      font-size: 14px;
      color: #9ca3af;
      margin-right: 6px;
    }

    .form-input {
      border: none;
      background: transparent;
      padding: 9px 4px;
      font-size: 13px;
      width: 100%;
      outline: none;
      color: #111827;
    }

    .btn-login {
      width: 100%;
      border: none;
      border-radius: 999px;
      background: linear-gradient(135deg, #6366f1, #ec4899);
      color: white;
      padding: 9px 0;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 10px 20px rgba(79, 70, 229, 0.45);
      transition: transform 0.1s ease, box-shadow 0.1s ease;
      margin-top: 4px;
      margin-bottom: 10px;
    }

    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 12px 24px rgba(79, 70, 229, 0.5);
    }

    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 6px 14px rgba(79, 70, 229, 0.38);
    }

    .bottom-text {
      text-align: center;
      font-size: 12px;
      color: #6b7280;
      margin-top: 4px;
    }

    .bottom-text a {
      color: #ec4899;
      font-weight: 500;
      text-decoration: none;
    }

    .bottom-text a:hover {
      text-decoration: underline;
    }

    .role-hint {
      font-size: 11px;
      text-align: center;
      color: #9ca3af;
      margin-top: 2px;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 22px 18px 18px;
      }
    }
  </style>
</head>
<body>

<div class="login-wrapper">
  <div class="login-card">
    <div class="login-header">
      <div class="login-badge">
        <span>💄</span>
        <span>Booking MUA</span>
      </div>
      <div class="login-brand">Buat akun dulu</div>
      <div class="login-title">Daftar sebagai customer</div>
      <div class="login-subtitle">
        Akun ini dipakai untuk melakukan booking dan melihat riwayat booking kamu.
      </div>
    </div>

    <?php if ($error): ?>
      <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label class="form-label">Username</label>
        <div class="input-shell">
          <span>@</span>
          <input
            type="text"
            name="username"
            class="form-input"
            required
          >
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-shell">
          <span>•••</span>
          <input
            type="password"
            name="password"
            class="form-input"
            required
          >
        </div>
      </div>

      <button type="submit" class="btn-login">
        Daftar Sekarang
      </button>

      <p class="bottom-text">
        Sudah punya akun?
        <a href="login.php">Masuk di sini</a>
      </p>

      <p class="role-hint">
        Akun yang dibuat lewat halaman ini otomatis memiliki role <strong>customer</strong>.
      </p>
    </form>
  </div>
</div>

</body>
</html>
