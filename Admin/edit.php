<?php

include_once '../Include/koneksi.php';

$id = $_GET['id'];

// AMBIL DATA

$query = mysqli_query($conn, "


SELECT p.*, c.*, dp.*, di.*, k.materi, k.deskripsi AS deskripsi_materi
FROM pekerjaan p

LEFT JOIN categories c
ON p.categories_id = c.id_categories

LEFT JOIN detail_pekerjaan dp
ON p.id_pekerjaan = dp.pekerjaan_id

LEFT JOIN detail_item di
ON dp.id_detail_pekerjaan = di.detail_pekerjaan_id

LEFT JOIN kursus k
ON p.id_pekerjaan = k.pekerjaan_id

WHERE p.id_pekerjaan = '$id'

");

$data = mysqli_fetch_assoc($query);

// UPDATE DATA

if(isset($_POST['update'])){

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

    // UPDATE GAMBAR

    if($_FILES['gambar']['name'] != ''){

        $nama_file = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        move_uploaded_file($tmp, "gambar_pekerjaan/" . $nama_file);

        mysqli_query($conn, "
        UPDATE pekerjaan
        SET image='$nama_file'
        WHERE id_pekerjaan='$id'
        ");
    }
    // UPDATE PEKERJAAN

    mysqli_query($conn, "

    UPDATE pekerjaan SET

    nama_pekerjaan='$nama_pekerjaan',
    categories_id='$categories_id',
    deskripsi='$deskripsi'

    WHERE id_pekerjaan='$id'

    ");

    // UPDATE DETAIL PEKERJAAN

    mysqli_query($conn, "

    UPDATE detail_pekerjaan SET

    pengertian='$pengertian'

    WHERE pekerjaan_id='$id'

    ");

    // UPDATE DETAIL ITEM

    mysqli_query($conn, "

    UPDATE detail_item SET

    judul_jenis='$judul_jenis',
    tugas_utama='$tugas_utama',
    teknologi='$teknologi',
    skill='$skill',
    tempat_kerja='$tempat_kerja',
    gaji_kisaran='$gaji_kisaran'

    WHERE detail_pekerjaan_id='".$data['id_detail_pekerjaan']."'

    ");

    // UPDATE KURSUS

    mysqli_query($conn, "

    UPDATE kursus SET

    materi='$video',
    deskripsi='$deskripsi_materi'

    WHERE pekerjaan_id='$id'

    ");

    echo "<script>
    alert('Data berhasil diupdate');
    window.location='Dashboard.php';
    </script>";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pekerjaan</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<div class="edit-page">
  <div class="page-header">
    <div>
      <h1>Edit Pekerjaan</h1>
      <p class="subtitle">Perbarui data pekerjaan dan materi kursus dengan tampilan yang lebih rapi.</p>
    </div>
    <a href="Dashboard.php" class="btn-secondary">Kembali</a>
  </div>

  <form method="post" enctype="multipart/form-data" class="edit-form">

    <div class="form-row">
      <label for="nama_pekerjaan">Nama Pekerjaan</label>
      <input id="nama_pekerjaan" type="text" name="nama_pekerjaan" value="<?= $data['nama_pekerjaan']; ?>">
    </div>

    <div class="form-row">
      <label for="categories_id">Kategori</label>
      <select id="categories_id" name="categories_id">

        <?php

        $cat = mysqli_query($conn, "SELECT * FROM categories");

        while($c = mysqli_fetch_assoc($cat)){

        ?>

        <option
        value="<?= $c['id_categories']; ?>"

        <?php
        if($c['id_categories'] == $data['categories_id']){
            echo "selected";
        }
        ?>

        >

        <?= $c['name']; ?>

        </option>

        <?php } ?>

    </select>
    </div>

    <div class="form-row">
      <label for="deskripsi">Deskripsi Singkat</label>
      <textarea id="deskripsi" name="deskripsi"><?= $data['deskripsi']; ?></textarea>
    </div>

    <div class="form-row">
      <label for="pengertian">Pengertian</label>
      <textarea id="pengertian" name="pengertian"><?= $data['pengertian']; ?></textarea>
    </div>

    <div class="form-row two-column">
      <div>
        <label for="judul_jenis">Judul Jenis</label>
        <input id="judul_jenis" type="text" name="judul_jenis" value="<?= $data['judul_jenis']; ?>">
      </div>
      <div>
        <label for="tugas_utama">Tugas Utama</label>
        <textarea id="tugas_utama" name="tugas_utama"><?= $data['tugas_utama']; ?></textarea>
      </div>
    </div>

    <div class="form-row two-column">
      <div>
        <label for="teknologi">Teknologi</label>
        <input id="teknologi" type="text" name="teknologi" value="<?= $data['teknologi']; ?>">
      </div>
      <div>
        <label for="skill">Skill</label>
        <input id="skill" type="text" name="skill" value="<?= $data['skill']; ?>">
      </div>
    </div>

    <div class="form-row two-column">
      <div>
        <label for="tempat_kerja">Tempat Kerja</label>
        <input id="tempat_kerja" type="text" name="tempat_kerja" value="<?= $data['tempat_kerja']; ?>">
      </div>
      <div>
        <label for="gaji_kisaran">Gaji Kisaran</label>
        <input id="gaji_kisaran" type="text" name="gaji_kisaran" value="<?= $data['gaji_kisaran']; ?>">
      </div>
    </div>

    <div class="form-row">
      <label for="video">Link / Video Materi</label>
      <input id="video" type="text" name="video" value="<?= $data['materi']; ?>">
    </div>

    <div class="form-row">
      <label for="deskripsi_materi">Deskripsi Materi</label>
      <textarea id="deskripsi_materi" name="deskripsi_materi"><?= $data['deskripsi_materi']; ?></textarea>
    </div>

    <div class="form-row form-image">
      <label>Gambar Saat Ini</label>
      <div class="image-preview">
        <img src="gambar_pekerjaan/<?= $data['image']; ?>" alt="Preview Gambar">
        <span><?= $data['image']; ?></span>
      </div>
    </div>

    <div class="form-row">
      <label for="gambar">Unggah Gambar Baru</label>
      <input id="gambar" type="file" name="gambar">
    </div>

    <div class="form-actions">
      <button type="submit" name="update" class="btn-primary">Update Data</button>
    </div>

  </form>
</div>

</body>
</html>