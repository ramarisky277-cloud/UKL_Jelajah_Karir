<?php
session_start();
include_once '../Include/koneksi.php';

// Cek jika form disubmit
if (isset($_POST['nama_pekerjaan'])) {
    $nama_pekerjaan = mysqli_real_escape_string($conn, $_POST['nama_pekerjaan']);
    $categories_id = mysqli_real_escape_string($conn, $_POST['categories_id']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $pengertian = mysqli_real_escape_string($conn, $_POST['pengertian']);
    $judul_jenis = mysqli_real_escape_string($conn, $_POST['judul_jenis']);
    $tugas_utama = mysqli_real_escape_string($conn, $_POST['tugas_utama']);
    $teknologi = mysqli_real_escape_string($conn, $_POST['teknologi']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $tempat_kerja = mysqli_real_escape_string($conn, $_POST['tempat_kerja']);
    $gaji_kisaran = mysqli_real_escape_string($conn, $_POST['gaji_kisaran']);

    $video = mysqli_real_escape_string($conn, $_POST['video']);
    $deskripsi_materi = mysqli_real_escape_string($conn, $_POST['deskripsi_materi']);

    // Proses Upload Gambar
    $nama_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $upload_dir = "gambar_pekerjaan/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Pindahkan file jika ada yang diupload
    if (!empty($nama_file)) {
        move_uploaded_file($tmp, $upload_dir . $nama_file);
    } else {
        $nama_file = ""; // kosongi jika tidak upload gambar
    }

    // 1. INSERT KE TABEL PEKERJAAN
    $sql_pekerjaan = "INSERT INTO pekerjaan (categories_id, nama_pekerjaan, image, deskripsi) 
                      VALUES ('$categories_id', '$nama_pekerjaan', '$nama_file', '$deskripsi')";

    if(mysqli_query($conn, $sql_pekerjaan)){
        $id_pekerjaan = mysqli_insert_id($conn);

        // 2. INSERT KE TABEL DETAIL PEKERJAAN
        $sql_detail_pekerjaan = "INSERT INTO detail_pekerjaan (pekerjaan_id, pengertian) 
                                 VALUES ('$id_pekerjaan', '$pengertian')";

        if(mysqli_query($conn, $sql_detail_pekerjaan)){
            $id_detail_pekerjaan = mysqli_insert_id($conn);

            // 3. INSERT KE TABEL DETAIL ITEM
            $sql_detail = "INSERT INTO detail_item (detail_pekerjaan_id, judul_jenis, tugas_utama, teknologi, skill, tempat_kerja, gaji_kisaran) 
                           VALUES ('$id_detail_pekerjaan', '$judul_jenis', '$tugas_utama', '$teknologi', '$skill', '$tempat_kerja', '$gaji_kisaran')";

            if(mysqli_query($conn, $sql_detail)){
                
                // 4. INSERT KE TABEL KURSUS
                $sql_kursus = "INSERT INTO kursus (pekerjaan_id, materi, deskripsi) 
                               VALUES ('$id_pekerjaan', '$video', '$deskripsi_materi')";

                if(mysqli_query($conn, $sql_kursus)){
                    echo "<script> alert('Semua data berhasil ditambahkan'); window.location.href='Pekerjaan.php'; </script>"; 
                    exit;
                } else {
                    die("Error kursus: " . mysqli_error($conn));
                }
            } else {
                die("Error detail_item: " . mysqli_error($conn));
            }
        } else {
            die("Error detail_pekerjaan: " . mysqli_error($conn));
        }
    } else {
        die("Error pekerjaan: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pekerjaan Baru</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>

<div class="edit-page">
  <div class="page-header">
    <div>
      <h1>Tambah Pekerjaan Baru</h1>
      <p class="subtitle">Masukkan data pekerjaan dan materi kursus secara lengkap.</p>
    </div>
    <a href="Pekerjaan.php" class="btn-secondary">Kembali</a>
  </div>

  <form method="post" enctype="multipart/form-data" class="edit-form">

    <div class="form-row">
      <label for="nama_pekerjaan">Nama Pekerjaan</label>
      <input id="nama_pekerjaan" type="text" name="nama_pekerjaan" placeholder="Contoh: Web Developer" required>
    </div>

    <div class="form-row">
      <label for="categories_id">Kategori</label>
      <select id="categories_id" name="categories_id" required>
        <option value="">Pilih Kategori</option>
        <?php
          $query_cat = mysqli_query($conn, "SELECT * FROM categories");
          while ($cat = mysqli_fetch_assoc($query_cat)) {
            echo "<option value='" . $cat['id_categories'] . "'>" . $cat['nama_categories'] . "</option>";
          }
        ?>
      </select>
    </div>

    <div class="form-row">
      <label for="deskripsi">Deskripsi Singkat</label>
      <textarea id="deskripsi" name="deskripsi" placeholder="Tulis deskripsi singkat pekerjaan..." required></textarea>
    </div>

    <div class="form-row">
      <label for="pengertian">Pengertian</label>
      <textarea id="pengertian" name="pengertian" placeholder="Tulis pengertian lengkap pekerjaan..." required></textarea>
    </div>

    <div class="form-row two-column">
      <div>
        <label for="judul_jenis">Judul Jenis</label>
        <input id="judul_jenis" type="text" name="judul_jenis" placeholder="Contoh: Jenis Profesi IT">
      </div>
      <div>
        <label for="tugas_utama">Tugas Utama</label>
        <textarea id="tugas_utama" name="tugas_utama" placeholder="Pisahkan data dengan koma (,)"></textarea>
      </div>
    </div>

    <div class="form-row two-column">
      <div>
        <label for="teknologi">Teknologi</label>
        <textarea id="teknologi" name="teknologi" placeholder="Pisahkan data dengan koma (,)"></textarea>
      </div>
      <div>
        <label for="skill">Skill</label>
        <textarea id="skill" name="skill" placeholder="Pisahkan data dengan koma (,)"></textarea>
      </div>
    </div>

    <div class="form-row two-column">
      <div>
        <label for="tempat_kerja">Tempat Kerja</label>
        <textarea id="tempat_kerja" name="tempat_kerja" placeholder="Pisahkan data dengan koma (,)"></textarea>
      </div>
      <div>
        <label for="gaji_kisaran">Gaji Kisaran</label>
        <input id="gaji_kisaran" type="text" name="gaji_kisaran" placeholder="Contoh: Rp 5.000.000 - Rp 10.000.000">
      </div>
    </div>

    <div class="form-row">
      <label for="video">Link / Video Materi</label>
      <input id="video" type="text" name="video" placeholder="https://youtube.com/...">
    </div>

    <div class="form-row">
      <label for="deskripsi_materi">Deskripsi Materi</label>
      <textarea id="deskripsi_materi" name="deskripsi_materi" placeholder="Tulis deskripsi materi video kursus..."></textarea>
    </div>

    <div class="form-row">
      <label for="gambar">Unggah Gambar Pekerjaan</label>
      <input id="gambar" type="file" name="gambar" accept="image/*" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-primary">Tambah Data Pekerjaan +</button>
    </div>

  </form>
</div>

</body>
</html>