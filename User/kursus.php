<?php
// 1. Jalankan session di bagian paling atas
session_start();
include '../Include/koneksi.php';

// Mengambil ID user dari session (pastikan ini sesuai dengan key session login Anda)
$id_user_login = isset($_SESSION['id_users']) ? $_SESSION['id_users'] : null;

if (!isset($_GET['id'])) {
    die("ID pekerjaan tidak ditemukan");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT 
        pekerjaan.*,
        categories.nama_categories,
        kursus.materi,
        kursus.deskripsi AS deskripsi_materi
    FROM pekerjaan
    LEFT JOIN categories ON pekerjaan.categories_id = categories.id_categories
    LEFT JOIN kursus ON pekerjaan.id_pekerjaan = kursus.pekerjaan_id
    WHERE pekerjaan.id_pekerjaan = $id
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die('Data tidak ditemukan');
}

$video = $data['materi'];
$video = str_replace("watch?v=", "embed/", $video);
$video = str_replace("https://youtu.be/", "https://www.youtube.com/embed/", $video);

// 2. Cek status keanggotaan user secara langsung ke database
$is_premium = false;

if ($id_user_login) {
    $user_query = mysqli_query($conn, "SELECT status_member FROM users WHERE id_users = $id_user_login");
    $user_data = mysqli_fetch_assoc($user_query);
    
    if ($user_data && $user_data['status_member'] === 'premium') {
        $is_premium = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="kursus.css">
    <title>Jelajah karir</title>
</head>

<body>
    <nav> 
        <img src="../Gambar/Nobg.png" alt="logo" class="logo">
        <ul class="navbar">
            <li><a href="home_page.php">Home</a></li>
            <li class="nav-kanan"><a href="../Login/Login.php">Login</a></li>
        </ul>
    </nav>
   <section class="hero">
 
    <div class="hero-video">
      
      <?php if ($is_premium): ?>
        <iframe width="100%" 
            height="400"
            src="<?= $video;?>"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
      <?php else: ?>
        <div class="premium-overlay">
            <div class="premium-box">
                <h3>Konten Eksklusif Premium 🔒</h3>
                <p>Maaf, video kursus ini hanya dapat diakses oleh member Premium. Silakan lakukan aktivasi akun Anda untuk mendapatkan akses penuh.</p>
                <?php if (!$id_user_login): ?>
                    <a href="../Login/Login.php" class="btn-premium">Login Terlebih Dahulu</a>
                <?php else: ?>
                    <a href="halaman_transaksi.php" class="btn-premium">Berlangganan Sekarang</a>
                <?php endif; ?>
            </div>
        </div>
        <div style="width:100%; height:400px; background:#1a1a1a;"></div>
      <?php endif; ?>

    </div>
 
    <div class="hero-text">
      <span class="badge"><?= $data['nama_categories']; ?></span>
      <h2>Temukan Karir Impianmu Bersama Jelajah Karir</h2>
      <p><?= $data['deskripsi_materi']; ?></p>
      <button type="button" class="btn-back" onclick="history.back()">Kembali</button>
    </div>
  </section>
 
  <footer>
        <p>&copy; 2024 Jelajah Karir. All rights reserved.</p>
  </footer>
</body>
</html>