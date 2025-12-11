<?php
require 'db.php';
require 'cek_customer.php';

$id_user = $_SESSION['user_id'];

if (isset($_GET['batal'])) {
    $id = (int)$_GET['batal'];
    mysqli_query(
        $koneksi,
        "UPDATE bookings SET status='batal' WHERE id=$id AND id_user=$id_user"
    );
    header("Location: booking_saya.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query(
        $koneksi,
        "DELETE FROM bookings WHERE id=$id AND id_user=$id_user"
    );
    header("Location: booking_saya.php");
    exit;
}

$sql = "SELECT b.*, m.nama AS nama_mua
        FROM bookings b
        JOIN mua m ON b.id_mua = m.id
        WHERE b.id_user=$id_user
        ORDER BY b.created_at DESC";
$q = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Saya</title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="admin-wrapper">
  <h2>Booking Saya</h2>

  <div class="table-wrapper">
    <table class="table-admin">
      <thead>
        <tr>
          <th>No</th>
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
          <td><?= htmlspecialchars($b['nama_mua']) ?></td>
          <td><?= $b['tanggal'] ?></td>
          <td><?= $b['jam'] ?></td>
          <td><?= nl2br(htmlspecialchars($b['alamat'])) ?></td>
          <td><?= ucfirst($b['status']) ?></td>
          <td>
            <?php if ($b['status'] !== 'batal'): ?>
              <a href="booking_saya.php?batal=<?= $b['id'] ?>"
                 class="btn-small"
                 onclick="return confirm('Batalkan booking ini?')">Batalkan</a>
            <?php endif; ?>
            <a href="booking_saya.php?hapus=<?= $b['id'] ?>"
               class="btn-small-danger"
               onclick="return confirm('Hapus booking ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
