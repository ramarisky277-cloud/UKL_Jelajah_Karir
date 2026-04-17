<?php
session_start();
include_once '../Include/koneksi.php'; // Hubungkan ke database



if (isset($_POST['nama']))  {

    $nama_produk = $_POST[''];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Ambil file
    $nama_file = basename($_FILES['gambar']['name']);
    $tmp = $_FILES['gambar']['tmp_name'];

    // Folder menyimpan gambar
    $upload_dir = 'gambar_produk/';

    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Path lengkap
    $target_file = $upload_dir . $nama_file;

    // Upload file (HANYA SEKALI!)
    if (move_uploaded_file($tmp, $target_file)) {

        $sql = "INSERT INTO products (nama_produk, stok, harga, deskripsi, gambar)
                VALUES ('$nama_produk', '$stok', '$harga', '$deskripsi', '$nama_file')";

        if (mysqli_query($conn, $sql)) {
            echo "Produk berhasil ditambahkan.";
        } else {
            echo "Error DB: " . mysqli_error($conn);
        }

    } else {
        echo "Upload gagal!";
        print_r($_FILES);
    }
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin - Jelajah Karir</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="layout">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="sidebar-logo">
     <img src="../Gambar/Nobg.png"  width="80%" alt="Logo">
    </div>
    <div class="nav-item active">Dashboard</div>
    <div class="nav-item">Pekerjaan</div>
  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <h1>Dahsboard</h1>
      <button class="btn-logout">Logout</button>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- STAT CARDS -->
      <div class="stat-cards">
        <div class="stat-card teal">
          <div class="label">Total pekerjaan</div>
          <div class="value">2002</div>
        </div>
        <div class="stat-card amber">
          <div class="label">Total kategory</div>
          <div class="value">9</div>
        </div>
        <div class="stat-card light">
          <div class="label">Total user</div>
          <div class="value">100</div>
        </div>
      </div>

      <!-- TABLE SECTION -->
      <div class="section-card">
        <div class="section-header">
          <h2>Daftar Pekerjaan baru</h2>
          <a class="add-link" href="#tambah_pekerjaan">Tambah Pekerjaan</a>
        </div>
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Nama Pekerjaan</th>
              <th>Deskripsi singkat</th>
              <th>Category</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>
                <div class="img-placeholder">
                  <img src="../Gambar/BG_FrondEN.jpg" alt="Web Development">
                </div>
              </td>
              <td>Web Development</td>
              <td>Profesi ini sangat dibutuhkan dalam membuat web side</td>
              <td>Ilmu Teknologi</td>
              <td>
                <button class="btn-edit">Edit</button>
                <button class="btn-hapus">Hapus</button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>
                <div class="img-placeholder empty"></div>
              </td>
              <td>Web Development</td>
              <td>Profesi ini sangat dibutuhkan dalam membuat web side</td>
              <td>Ilmu Teknologi</td>
              <td>
                <button class="btn-edit">Edit</button>
                <button class="btn-hapus">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- FORM TAMBAH PEKERJAAN -->
       <section class="tambah_pekerjaan">
        <h2>Tambah Pekerjaan</h2>
      <form method="post" enctype="multipart/form-data">
      <div class="form-card">
        <div class="form-group">
          <label for="nama_pekerjaan">Nama Pekerjaan</label>
          <input type="text" id="namapekerjaan" name="nama_pekerjaan" placeholder="">
        </div>
        <div class="form-group">
          <label for="category">Category</label>
          <input type="text" id="category" name="category" placeholder="">
        </div>
        <div class="form-group">
          <label for="deskripsi">Deskripsi singkat</label>
          <input type="text" id="deskripsi" name="deskripsi" placeholder="">
        </div>
        <div class="form-group">
          <label>Gambar pekerjaan</label>
          <div class="file-input-wrap" onclick="document.getElementById('fileInput').click()">
            <span class="file-btn">choise file</span>
            <span class="file-name" id="fileName">No file chosen</span>
          </div>
          <input type="file" id="fileInput" accept="image/*" onchange="document.getElementById('fileName').textContent = this.files[0]?.name || 'No file chosen'">
        </div>
        <button class="btn-tambah">tambah Pekerjaan +</button>
      </div>
         </section>
    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /layout -->

</body>
</html>