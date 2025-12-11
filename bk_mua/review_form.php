<?php
require 'db.php';
require 'cek_customer.php';

$id_mua = isset($_GET['id_mua']) ? (int)$_GET['id_mua'] : 0;
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mua WHERE id=$id_mua"));
if (!$m) {
    die("MUA tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user  = $_SESSION['user_id'];
    $rating   = (int)$_POST['rating'];
    $komentar = mysqli_real_escape_string($koneksi, $_POST['komentar']);

    mysqli_query(
        $koneksi,
        "INSERT INTO reviews(id_user,id_mua,rating,komentar)
         VALUES($id_user,$id_mua,$rating,'$komentar')"
    );

    // update rata rata rating
    $avg = mysqli_fetch_row(
        mysqli_query($koneksi, "SELECT AVG(rating) FROM reviews WHERE id_mua=$id_mua")
    )[0];

    mysqli_query(
        $koneksi,
        "UPDATE mua SET rating=" . round($avg, 1) . " WHERE id=$id_mua"
    );

    header("Location: detail_mua.php?id=$id_mua");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Beri Ulasan - <?= htmlspecialchars($m['nama']) ?></title>
  <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

<?php include 'navbar_customer_admin.php'; ?>

<div class="form-wrapper">
  <h2>Beri Ulasan - <?= htmlspecialchars($m['nama']) ?></h2>

  <form method="POST" class="form-card">
    <label>Rating</label>
    <select name="rating" required>
      <option value="">Pilih rating</option>
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
      <?php endfor; ?>
    </select>

    <label>Komentar</label>
    <textarea name="komentar" rows="3"></textarea>

    <div class="form-actions">
      <a href="detail_mua.php?id=<?= $m['id'] ?>" class="btn-pink-outline">Batal</a>
      <button type="submit" class="btn-pink">Kirim Ulasan</button>
    </div>
  </form>
</div>

</body>
</html>
