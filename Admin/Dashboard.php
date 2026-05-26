<?php

session_start();
include_once '../Include/koneksi.php';

if (isset($_POST['nama_pekerjaan'])) {

    $nama_pekerjaan = $_POST['nama_pekerjaan'];
    $categories_id = $_POST['categories_id'];
    $deskripsi = $_POST['deskripsi'];

    $pengertian = $_POST['pengertian'];
    $judul_jenis = $_POST['judul_jenis'];
    $tugas_utama = $_POST['tugas_utama'];
    $teknologi = $_POST['teknologi'];
    $skill = $_POST['skill'];
    $tempat_kerja = $_POST['tempat_kerja'];
    $gaji_kisaran = $_POST['gaji_kisaran'];

    $video = $_POST['video'];
    $deskripsi_materi = $_POST['deskripsi_materi'];

    // upload gambar
    $nama_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $upload_dir = "gambar_pekerjaan/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    move_uploaded_file($tmp, $upload_dir . $nama_file);

    // INSERT PEKERJAAN

    $sql_pekerjaan = "INSERT INTO pekerjaan
    (categories_id, nama_pekerjaan, image, deskripsi)

    VALUES

    ('$categories_id', '$nama_pekerjaan', '$nama_file', '$deskripsi')";

    if(mysqli_query($conn, $sql_pekerjaan)){

        // ambil id pekerjaan
        $id_pekerjaan = mysqli_insert_id($conn);


        // INSERT DETAIL PEKERJAAN

        $sql_detail_pekerjaan = "INSERT INTO detail_pekerjaan
        (pekerjaan_id, pengertian)

        VALUES

        ('$id_pekerjaan', '$pengertian')";

        if(mysqli_query($conn, $sql_detail_pekerjaan)){

            // ambil id detail pekerjaan
            $id_detail_pekerjaan = mysqli_insert_id($conn);

            // INSERT DETAIL ITEM

            $sql_detail = "INSERT INTO detail_item
            (detail_pekerjaan_id, judul_jenis, tugas_utama, teknologi, skill, tempat_kerja, gaji_kisaran)

            VALUES

            ('$id_detail_pekerjaan', '$judul_jenis', '$tugas_utama', '$teknologi', '$skill', '$tempat_kerja', '$gaji_kisaran')";

            if(mysqli_query($conn, $sql_detail)){

                // INSERT KURSUS
          
                $sql_kursus = "INSERT INTO kursus
                (pekerjaan_id, materi, deskripsi)

                VALUES

                ('$id_pekerjaan', '$video', '$deskripsi_materi')";

                if(mysqli_query($conn, $sql_kursus)){

                    echo "<script> alert('Semua data berhasil ditambahkan'); window.location.href='Dashboard.php'; </script> "; exit;
                }else{
                    die("Error kursus: " . mysqli_error($conn));
                }

            }else{
                die("Error detail_item: " . mysqli_error($conn));
            }

        }else{
            die("Error detail_pekerjaan: " . mysqli_error($conn));
        }

    }else{
        die("Error pekerjaan: " . mysqli_error($conn));
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
    <div class="sidebar">
    <div class="nav-item"><a href="Dashboard.php" style="color:inherit; text-decoration:none;">Dashboard</a></div>
    <div class="nav-item"><a href="Pekerjaan.php" style="color:inherit; text-decoration:none;">Pekerjaan</a></div>
    <div class="nav-item"><a href="Transaksi.php" style="color:inherit; text-decoration:none;">Transaksi</a></div>
  </div>
  </div>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
    <h1>Dahsboard Admin</h1>
    <div class="topbar-right">
      <span class="greet">Halo, <strong>Admin</strong></span>
      <button class="btn-logout"><a href="../Login/Login.php">Logout</a></button>
    </div>
  </div>


    <!-- CONTENT -->
    <div class="content">

      <!-- STAT CARDS -->
      <div class="stat-cards">
        <div class="stat-card teal">
          <div class="label">Total pekerjaan</div>
          <div class="value">
              <?php
        $query = "SELECT COUNT(*) AS db_ukl FROM pekerjaan";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);

        echo $data['db_ukl'];
        ?>
          </div>
        </div>
        <div class="stat-card amber">
          <div class="label">Total kategory</div>
          <div class="value">9</div>
        </div>
        <div class="stat-card light">
          <div class="label">
          <h3>Total user <h3></div>
          <p class="value">
          <?php
        $query = "SELECT COUNT(*) AS db_ukl FROM users";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);

        echo $data['db_ukl'];
        ?>
    </p>
  
        </div>
      </div>

      <!-- TABLE SECTION -->
      <div class="section-card">
        <div class="section-header">
          <h2>Daftar Pekerjaan baru</h2>
          <a class="add-link" href="tambah.php">Tambah Pekerjaan +</a>
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
<?php
  $query = mysqli_query($conn, "
  SELECT * 
FROM pekerjaan as p
JOIN categories as c
ON p.categories_id = c.id_categories

ORDER BY p.id_pekerjaan DESC
LIMIT 10
");

$no = 1;

while ($data = mysqli_fetch_assoc($query)) 
   {
?>
  <tr>
    <td><?= $no++; ?></td>
    <td>
      <div class="img-placeholder">
        <img src="gambar_pekerjaan/<?= $data['image']; ?>" width="80">
      </div>
    </td>
    <td><?= $data['nama_pekerjaan']; ?></td>
    <td><?= $data['deskripsi']; ?></td>
    <td><?= $data['nama_categories']; ?></td>
    <td>
    <a class="btn-edit" href="edit.php?id=<?= $data['id_pekerjaan']; ?>">
    Edit
  </a>
  <a class="btn-hapus" href="hapus.php?id=<?= $data['id_pekerjaan']; ?>">
    Hapus
</a>
  </td>
</tr>
<?php } ?>
</tbody>
        </table>
      </div>

      
<footer>
        <p>&copy; 2024 Jelajah Karir. All rights reserved.</p>
  </footer>

</body>
</html>