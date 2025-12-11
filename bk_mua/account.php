<?php
require 'db.php';
require 'cek_customer.php';

$user_id = $_SESSION['user_id'];

// ambil data user saat ini
$qUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $user_id LIMIT 1");
$user  = mysqli_fetch_assoc($qUser);

if (!$user) {
    die("Data user tidak ditemukan.");
}

$success = "";
$error   = "";

// proses update akun
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $username_baru   = trim($_POST['username'] ?? '');
    $password_baru   = trim($_POST['new_password'] ?? '');
    $ulang_password  = trim($_POST['confirm_password'] ?? '');

    // validasi sederhana
    if ($username_baru === '') {
        $error = "Username tidak boleh kosong.";
    } else {
        // cek kalau username diubah, jangan sampai duplikat
        if ($username_baru !== $user['username']) {
            $cek = mysqli_query(
                $koneksi,
                "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($koneksi, $username_baru) . "' LIMIT 1"
            );
            if (mysqli_num_rows($cek) > 0) {
                $error = "Username sudah dipakai, coba yang lain.";
            }
        }

        // validasi password baru kalau diisi
        if (!$error && $password_baru !== '') {
            if ($password_baru !== $ulang_password) {
                $error = "Konfirmasi password baru tidak sama.";
            } elseif (strlen($password_baru) < 4) {
                $error = "Password baru minimal 4 karakter.";
            }
        }
    }

    // jika tidak ada error → update
    if (!$error) {
        $username_sql = mysqli_real_escape_string($koneksi, $username_baru);

        if ($password_baru !== '') {
            $password_sql = mysqli_real_escape_string($koneksi, $password_baru);
            $sql = "UPDATE users 
                    SET username='$username_sql', password='$password_sql'
                    WHERE id=$user_id";
        } else {
            $sql = "UPDATE users 
                    SET username='$username_sql'
                    WHERE id=$user_id";
        }

        if (mysqli_query($koneksi, $sql)) {
            $success = "Pengaturan akun berhasil diperbarui.";
            // update data di session & objek user
            $_SESSION['username'] = $username_baru;
            $user['username']     = $username_baru;
            if ($password_baru !== '') {
                $user['password'] = $password_baru;
            }
        } else {
            $error = "Terjadi kesalahan saat menyimpan data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Atur Akun</title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="account-wrapper">
  <div class="form-card" style="max-width:560px;margin:32px auto;">
    <h2 style="margin-bottom:4px;">Pengaturan Akun</h2>
    <p class="text-muted" style="margin-bottom:12px;">
      Ubah username dan password akun kamu di sini.
    </p>

    <?php if ($error): ?>
      <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="update_account" value="1">

      <label>Username</label>
      <input type="text" name="username"
             value="<?= htmlspecialchars($user['username']) ?>" required>

      <label>Password baru <span class="text-muted">(opsional)</span></label>
      <input type="password" name="new_password" placeholder="Kosongkan jika tidak ingin mengubah">

      <label>Ulangi password baru</label>
      <input type="password" name="confirm_password" placeholder="Isi jika mengganti password">

      <div class="form-actions" style="margin-top:12px;justify-content:flex-end;">
        <a href="home.php" class="btn-pink-outline">Batal</a>
        <button type="submit" class="btn-pink">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
