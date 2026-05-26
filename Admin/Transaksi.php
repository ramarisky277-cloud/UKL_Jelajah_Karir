<?php
session_start();
include_once '../Include/koneksi.php';

// Cek apakah yang login adalah admin
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header('Location: ../Login/Login.php');
    exit();
}

// Proses jika admin ingin mengubah status pembayaran secara manual
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_trans = intval($_GET['id']);
    $action = $_GET['action']; // 'success' atau 'failed'

    if ($action === 'success' || $action === 'failed') {
        // Update status transaksi
        $query_update = mysqli_query($conn, "UPDATE transactions SET payment_status = '$action' WHERE id_transaction = $id_trans");
        
        if ($query_update && $action === 'success') {
            // Jika disetujui sukses, ambil user_id dari transaksi ini untuk diupdate ke premium
            $get_user = mysqli_query($conn, "SELECT user_id FROM transactions WHERE id_transaction = $id_trans");
            $u_data = mysqli_fetch_assoc($get_user);
            if ($u_data) {
                $uid = $u_data['user_id'];
                mysqli_query($conn, "UPDATE users SET status_member = 'premium' WHERE id_users = $uid");
            }
        }
        header("Location: Transaksi.php");
        exit();
    }
}

// Ambil semua data transaksi digabung dengan nama user
$query_transaksi = mysqli_query($conn, "
    SELECT t.*, u.username 
    FROM transactions t
    JOIN users u ON t.user_id = u.id_users
    ORDER BY t.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin - Transaksi</title>
  <link rel="stylesheet" href="transaksi.css"> 
  </head>
<body>

<div class="layout">

  <div class="sidebar">
    <div class="sidebar-logo">
     <img src="../Gambar/Nobg.png" width="80%" alt="Logo">
    </div>
    <div class="nav-item"><a href="Dashboard.php" style="color:inherit; text-decoration:none;">Dashboard</a></div>
    <div class="nav-item"><a href="Pekerjaan.php" style="color:inherit; text-decoration:none;">Pekerjaan</a></div>
    <div class="nav-item active"><a href="Transaksi.php" style="color:inherit; text-decoration:none;">Transaksi</a></div>
  </div>

  <div class="main">

    <div class="topbar">
      <h1>Data Aktivasi Langganan</h1>
      <div class="topbar-right">
        <span class="greet">Halo, <strong>Admin</strong></span>
        <button class="btn-logout">
          <a href="../Login/Login.php">Logout</a>
        </button>
      </div>
    </div>

    <div class="page-body">
      <div class="table-wrapper">
        <h2 class="judul-kategori">Riwayat Transaksi Masuk</h2>
        <div class="table-scroll">
          <table>
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Username</th>
                      <th>Nominal</th>
                      <th>Status</th>
                      <th>Tanggal</th>
                      <th>Aksi Kendali</th>
                  </tr>
              </thead>
              <tbody>
                <?php 
                $no = 1;
                while($row = mysqli_fetch_assoc($query_transaksi)): 
                ?>
                  <tr>
                      <td><?= $no++; ?></td>
                      <td><?= htmlspecialchars($row['username']); ?></td>
                      <td>Rp <?= number_format($row['amount'], 0, ',', '.'); ?></td>
                      <td>
                          <span class="status-badge status-<?= $row['payment_status']; ?>">
                              <?= ucfirst($row['payment_status']); ?>
                          </span>
                      </td>
                      <td><?= $row['created_at']; ?></td>
                      <td>
                          <?php if($row['payment_status'] == 'pending'): ?>
                              <a href="Transaksi.php?action=success&id=<?= $row['id_transaction']; ?>" class="btn-action btn-approve">Setujui</a>
                              <a href="Transaksi.php?action=failed&id=<?= $row['id_transaction']; ?>" class="btn-action btn-reject">Tolak</a>
                          <?php else: ?>
                              <span style="color: #aaa; font-size:12px; font-weight:600;">Selesai</span>
                          <?php endif; ?>
                      </td>
                  </tr>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($query_transaksi) == 0): ?>
                    <tr><td colspan="6" style="text-align:center;">Belum ada riwayat transaksi.</td></tr>
                <?php endif; ?>
              </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>