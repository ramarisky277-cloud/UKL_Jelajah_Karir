
<?php

session_start();
include_once '../Include/koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin - Jelajah Karir</title>
    <link rel="stylesheet" href="pekerjaan.css">
</head>
<body>

<div class="layout">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="sidebar-logo">
     <img src="../Gambar/Nobg.png"  width="80%" alt="Logo">
    </div>
    <div class="sidebar">
    <div class="nav-item"><a href="Dashboard.php" style="color:inherit; text-decoration:none;">Dashboard</a></div>
    <div class="nav-item"><a href="Pekerjaan.php" style="color:inherit; text-decoration:none;">Pekerjaan</a></div>
    <div class="nav-item"><a href="Transaksi.php" style="color:inherit; text-decoration:none;">Transaksi</a></div>
  </div>
  </div>

<!-- ── MAIN ── -->
<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <h1>Pekerjaan </h1>
    <div class="topbar-right">
      <span class="greet">Halo, <strong>Admin</strong></span>
      <button class="btn-logout"><a href="../Login/Login.php">Logout</a></button>
    </div>
  </div>

  <!-- PAGE BODY -->
  <div class="page-body">

    <!-- SECTION: Bidang IT -->
    <div class="section-card">
      <div class="section-title"></div>
      <div class="table-wrap">
        <?php
$queryKategori = mysqli_query($conn, "
    SELECT * FROM categories
");

while($kategori = mysqli_fetch_assoc($queryKategori)){

    $idKategori = $kategori['id_categories'];
?>

<div class="table-wrapper">

    <!-- JUDUL CATEGORY -->
    <h2 class="judul-kategori">
        <?= $kategori['nama_categories']; ?>
    </h2>
  <div class="table-scroll">
    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Pekerjaan</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

<?php
$queryPekerjaan = mysqli_query($conn, "
    SELECT * FROM pekerjaan
    WHERE categories_id = '$idKategori'
    
");

$no = 1;

while($data = mysqli_fetch_assoc($queryPekerjaan)){
?>

<tr>

    <td><?= $no++; ?></td>
    <td>
        <img src="gambar_pekerjaan/<?= $data['image']; ?>" width="80">
    </td>
    <td><?= $data['nama_pekerjaan']; ?></td>
    <td><?= $data['deskripsi']; ?></td>
    <td>
        <div style="display: flex !important; gap: 8px !important; align-items: center;">
        <a href="edit.php?id=<?= $data['id_pekerjaan']; ?>" 
           class="btn-edit">
           Edit
        </a>

        <a href="hapus.php?id=<?= $data['id_pekerjaan']; ?>" 
           class="btn-hapus">
           Hapus
        </a>
        </div>
    </td>
</tr>
<?php } ?>
        </tbody>
    </table>
  </div>
</div>
<?php } ?>
      </div>
    </div>
    
    </div>

  </div>
</div>

<footer>
        <p>&copy; 2024 Jelajah Karir. All rights reserved.</p>
  </footer>

</body>
</html>