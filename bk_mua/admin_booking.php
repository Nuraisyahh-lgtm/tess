<?php
require 'db.php';
require 'cek_admin.php';

/* ===========================================
   1. HANDLE UPDATE STATUS DARI TOMBOL
   =========================================== */
if (isset($_GET['id'], $_GET['status'])) {
    $id     = (int)$_GET['id'];
    $status = $_GET['status'];

    // Amankan: hanya boleh 3 status ini
    $allowed = ['pending', 'batal', 'selesai'];
    if ($id > 0 && in_array($status, $allowed)) {
        mysqli_query($koneksi,
            "UPDATE bookings SET status='$status' WHERE id=$id"
        );
    }

    // redirect supaya URL bersih & cegah refresh dobel update
    header("Location: admin_booking.php");
    exit;
}

/* ===========================================
   2. AMBIL DATA BOOKING
   =========================================== */
$sql = "SELECT b.*, u.username, m.nama AS nama_mua
        FROM bookings b
        JOIN users u ON b.id_user = u.id
        JOIN mua   m ON b.id_mua = m.id
        ORDER BY b.created_at DESC";
$q = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - Data Booking</title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="admin-wrapper">
  <div class="admin-header">
    <div>
      <h2>Data Booking</h2>
      <p class="text-muted">
        Daftar semua booking yang masuk. Ubah status menjadi <strong>Selesai</strong> atau <strong>Batal</strong>.
      </p>
    </div>
  </div>

  <div class="table-wrapper">
    <table class="table-admin">
      <thead>
        <tr>
          <th>No</th>
          <th>Customer</th>
          <th>MUA</th>
          <th>Tanggal</th>
          <th>Jam</th>
          <th>Alamat</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($b = mysqli_fetch_assoc($q)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($b['username']) ?></td>
          <td><?= htmlspecialchars($b['nama_mua']) ?></td>
          <td><?= $b['tanggal'] ?></td>
          <td><?= $b['jam'] ?></td>
          <td><?= nl2br(htmlspecialchars($b['alamat'])) ?></td>
          <td>
            <?php
              // badge status biar jelas warnanya
              $cls = 'badge-pending';
              if ($b['status'] === 'selesai') $cls = 'badge-done';
              if ($b['status'] === 'batal')   $cls = 'badge-cancel';
            ?>
            <span class="<?= $cls ?>">
              <?= ucfirst($b['status']) ?>
            </span>
          </td>
          <td>
            <?php if ($b['status'] !== 'selesai'): ?>
              <a href="admin_booking.php?id=<?= $b['id'] ?>&status=selesai"
                 class="btn-small"
                 style="background:#22c55e;color:#fff;"
                 onclick="return confirm('Tandai booking ini sebagai SELESAI?');">
                 Selesai
              </a>
            <?php endif; ?>

            <?php if ($b['status'] !== 'batal'): ?>
              <a href="admin_booking.php?id=<?= $b['id'] ?>&status=batal"
                 class="btn-small-danger"
                 onclick="return confirm('Batalkan booking ini?');">
                 Batalkan
              </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
