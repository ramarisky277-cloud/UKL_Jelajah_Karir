<?php
session_start();
include_once '../Include/koneksi.php';

// Cek apakah user sudah login. Jika belum, lempar ke halaman login
if (!isset($_SESSION['id_users'])) {
    header("Location: ../Login/Login.php");
    exit();
}

$id_user_login = $_SESSION['id_users'];
$username_login = $_SESSION['username'];

// Ambil status member terbaru dari database
$query_user = mysqli_query($conn, "SELECT status_member FROM users WHERE id_users = $id_user_login");
$data_user = mysqli_fetch_assoc($query_user);

// Jika user ternyata sudah premium, kembalikan ke home
if ($data_user['status_member'] === 'premium') {
    header("Location: home_page.php");
    exit();
}

$pesan = "";

// Proses ketika user menekan tombol "Bayar Sekarang"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proses_bayar'])) {
    $amount = 50000; // Contoh harga langganan Rp 50.000
    $payment_status = 'success'; // Kita set 'success' untuk simulasi pembayaran instan

    // 1. Masukkan data ke tabel transactions
    $stmt_trans = $conn->prepare("INSERT INTO transactions (user_id, amount, payment_status) VALUES (?, ?, ?)");
    $stmt_trans->bind_param("iis", $id_user_login, $amount, $payment_status);
    
    if ($stmt_trans->execute()) {
        // 2. Jika transaksi berhasil, langsung update status_member di tabel users menjadi 'premium'
        $stmt_user = $conn->prepare("UPDATE users SET status_member = 'premium' WHERE id_users = ?");
        $stmt_user->bind_param("i", $id_user_login);
        
        if ($stmt_user->execute()) {
            $pesan = "<div class='alert success'>🎉 Pembayaran Berhasil! Akun Anda sekarang menjadi PREMIUM. Silakan kembali ke kelas kursus.</div>";
        } else {
            $pesan = "<div class='alert error'>Gagal memperbarui status keanggotaan: " . $conn->error . "</div>";
        }
        $stmt_user->close();
    } else {
        $pesan = "<div class='alert error'>Gagal mencatat transaksi: " . $conn->error . "</div>";
    }
    $stmt_trans->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="halaman_transaksi.css">
    <title>Langganan Premium - Jelajah Karir</title>
</head>
<body>

<div class="container">
    <h2>Aktivasi Akun Premium 🚀</h2>
    <p>Halo, <strong><?= htmlspecialchars($username_login); ?></strong>! Selangkah lagi untuk membuka semua video kursus eksklusif.</p>
    
    <?= $pesan; ?>

    <?php if (!isset($_POST['proses_bayar']) || strpos($pesan, 'Gagal') !== false): ?>
        <div class="price">Rp 50.000 <span style="font-size:14px; color:#aaa;">/ selamanya</span></div>
        
        <ul class="benefit">
            <li>Akses semua video tutorial karir</li>
            <li>Bebas iklan & bebas batasan durasi</li>
            <li>Sertifikat digital setelah lulus kelas</li>
        </ul>

        <form action="" method="POST">
            <button type="submit" name="proses_bayar" class="btn-pay">Simulasi Bayar Sekarang</button>
        </form>
    <?php endif; ?>

    <a href="home_page.php" class="btn-back">← Kembali ke Home</a>
</div>

</body>
</html>