<?php

include '../Include/koneksi.php';
session_start();

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 1;

$query = "SELECT p.*, dp.pengertian AS dp_pengertian, dp.prospek_kerja, dp.id_detail_pekerjaan
          FROM pekerjaan p
          LEFT JOIN detail_pekerjaan dp ON dp.pekerjaan_id = p.id_pekerjaan
          WHERE p.id_pekerjaan = '$id'";

$result = mysqli_query($conn, $query);
$detail_pekerjaan = mysqli_fetch_assoc($result);

$items = []; // Inisialisasi array kosong agar tidak error di bagian loop bawah

if ($detail_pekerjaan && !empty($detail_pekerjaan['id_detail_pekerjaan'])) {
    //  query detail_item HANYA JIKA id_detail_pekerjaan ditemukan
    $id_detail = $detail_pekerjaan['id_detail_pekerjaan'];
    $query_item = "SELECT * FROM detail_item WHERE detail_pekerjaan_id = '$id_detail'";
    $result_item = mysqli_query($conn, $query_item);

    while ($row = mysqli_fetch_assoc($result_item)) {
        $row['teknologi_arr'] = !empty($row['teknologi']) ? explode(",", $row['teknologi']) : [];
        $row['skill_arr'] = !empty($row['skill']) ? explode(",", $row['skill']) : [];
        $row['tugas_arr'] = !empty($row['tugas_utama']) ? explode(",", $row['tugas_utama']) : [];
        $row['tempat_arr'] = !empty($row['tempat_kerja']) ? explode(",", $row['tempat_kerja']) : [];
        $items[] = $row;
    }
} else {
   
}

if (!isset($_GET['id'])) {
    die("ID pekerjaan tidak ditemukan");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT 
        pekerjaan.*,
        categories.nama_categories,
        detail_pekerjaan.id_detail_pekerjaan,
        detail_pekerjaan.pengertian AS pengertian_utama,
        detail_pekerjaan.prospek_kerja,
        detail_item.*
        
    FROM pekerjaan
    
    LEFT JOIN categories 
        ON pekerjaan.categories_id = categories.id_categories
        
    LEFT JOIN detail_pekerjaan 
        ON pekerjaan.id_pekerjaan = detail_pekerjaan.pekerjaan_id
        
    LEFT JOIN detail_item 
        ON detail_pekerjaan.id_detail_pekerjaan = detail_item.detail_pekerjaan_id
        
    WHERE pekerjaan.id_pekerjaan = $id
");

$data = mysqli_fetch_assoc($query);

$category_id = $data['categories_id'];

$querySidebar = mysqli_query($conn, "
    SELECT *
    FROM pekerjaan
    WHERE categories_id = '$category_id'
    AND id_pekerjaan != '$id'
");

if (!$data) {
    die("Data tidak ditemukan");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="detail.css">
    <title>Jelajah karir</title>
</head>
<body>
    <nav> 
        <img src="../Gambar/Nobg.png" alt="logo" class="logo">
        <ul class="navbar">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="#detail-panel">Categories</a></li>
            <li><a href="#pekerjaan">Pekerjaan</a></li>
            <li class="nav-kanan"><a href="../Login/Login.php">login</a></li>
        </ul>
    </nav>
    <section class="home_page">
        <div class="deco-teal"></div>
        <div class="deco-gold"></div>
        <div class="home_page-container">
            <h1 class="home_title">Langkah awal menuju kesuksesan</h1>
            <p class="home_subtitle">Kenali dirimu, kenali </p>
            <p class="home_desk">Masa depan tidak harus ditentukan hari ini, tetapi 
                mengenalnya sejak dini akan membuat langkahmu
                 lebih terarah. Website ini hadir untuk 
                 membantumu mengenal berbagai bidang <mark>karier</mark> secara sederhana dan mudah dipahami.</p>
            <a href="#detail-panel" class="btn-primary">Mulai Eksplorasi &rarr;</a>
        </div>
        <div class="hero-image">
            <img src="../Gambar/Grafis_orang_login.png" alt="Grafis">
        </div>
    </section>
    <!-- Bagian Detail -->
    <div class="page-header">
    <h1>Caritau apa bidang mu?</h1>
    </div>


<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <!-- Card 1 (active) -->

    <div class="sidebar-scroll">
    <?php while($sidebar = mysqli_fetch_assoc($querySidebar)) { ?>
<div class="card active">
    <div class="card-img">
        <img 
            src="../Admin/gambar_pekerjaan/<?= $sidebar['image']; ?>" 
            alt="<?= $sidebar['nama_pekerjaan']; ?>">
        <span class="pk-badge dark-badge">
            <?= $data['nama_categories']; ?>
        </span>
    </div>

    <div class="card-body">
        <h3><?= $sidebar['nama_pekerjaan']; ?></h3>
        <p>
            <?= $sidebar['deskripsi']; ?>
        </p>
        <a 
            href="detail.php?id=<?= $sidebar['id_pekerjaan']; ?>" 
            class="btn-detail"> View Details
        </a>
    </div>
</div>
<?php } ?>
</div>
  </aside>

  <!-- DETAIL PANEL -->
  <main class="detail-panel">

    <div class="detail-img">
    <img src="../Admin/gambar_pekerjaan/<?= $data['image']; ?>" alt="<?= $data['nama_pekerjaan']; ?>">
</div>

    <div class="detail-content">
      <?php
       
      ?>
      <h2><?php echo $data['nama_pekerjaan']; ?></h2>

      <div class="section-block" id="pengertian">
        <h4>Pengertian</h4>
        <p style="margin: top 8px;"><?= $data['pengertian']; ?></p>
        <p style="margin-top:8px;">Mereka bekerja menggunakan berbagai bahasa pemrograman dan teknologi web untuk memastikan website:</p>
        <ul style="margin-top:6px;">
          <li>Bisa dibuka di browser (Chrome, Firefox, dll)</li>
          <li>Cepat dan responsif</li>
          <li>Aman digunakan</li>
          <li>Mudah dipakai oleh user (user-friendly)</li>
        </ul>
      </div>


      <hr class="divider"/>

      <div class="section-block">
        <h4>Jenis Jenis</h4>
        <div class="jenis-grid">
          <div class="jenis-card">
            <h5><?= $data['judul_jenis']; ?></h5>
            <p><strong>Tugas utama:</strong></p>
            <?php
              $tugas = explode(",", $data['tugas_utama']);
            ?>
            <ul>
            <?php foreach($tugas as $item): ?>
                <li><?= trim($item); ?></li>
            <?php endforeach; ?>
            </ul>
            <p style="margin-top:8px;"><strong>Teknologi:</strong></p>
            <?php
              $teknologi = explode(",", $data['teknologi']);
            ?>
            <ul>
            <?php foreach($teknologi as $item): ?>
                <li><?= trim($item); ?></li>
            <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <hr class="divider"/>

      <div class="section-block">
        <h4>Tugas Utama Profesi ini</h4>
        <?php
              $tugas = explode(",", $data['tugas_utama']);
            ?>
        <ul>
            <?php foreach($tugas as $item): ?>
                <li><?= trim($item); ?></li>
            <?php endforeach; ?>
         </ul>
      </div>

      <hr class="divider"/>

      <div class="section-block">
        <h4>Skill yang Harus Dimiliki</h4>
        <div class="skill-tags">
          <?php
          $tempat = explode(",", $data['tempat_kerja']);
          foreach($tempat as $item){
          ?>
          <span class="skill-tag">
                <?= trim($item); ?>
            </span>
          <?php } ?>
        </div>
      </div>

      <hr class="divider"/>
      
      <div class="section-block">
        <h4>Tempat Kerja</h4>
        <?php
              $tempat = explode(",", $data['tempat_kerja']);
            ?>
        <ul>
          <?php foreach($tempat as $item): ?>
            <li><?= trim($item); ?></li>
          <?php endforeach; ?>
</ul>
      </div>

      <hr class="divider"/>

      <div class="section-block">
        <h4>Gaji di Indonesia (kisaran)</h4>
        <div class="salary-chips">
          <span class="chip junior">
              <?= $data['gaji_kisaran']; ?>
          </span>
        </div>
      </div>
      <a href="kursus.php?id=<?= $data['id_pekerjaan']; ?>" class="kursus-link">
    Lihat Penejalasan Vidio
</a>
      <a href="home_page.php" class="kursus-link"> Kembali ke Home</a>
    </div> 
  </main>

</div>

    <section class="pekerjaan" id="pekerjaan">
        <div class="pekerjaan-container">
            <h1>Pekerjaan</h1>

            <div class="search-box">
                <form method="GET" action="#pekerjaan" class="search-box">
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Search"
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                    >
                    <button type="submit">Cari</button>
                </form>
            </div>

            <div class="pekerjaan-grid">
            <?php
            $search    = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
            $pekerjaan = $_GET['pekerjaan'] ?? [];
            $pekerjaan = array_filter($pekerjaan, fn($v) => $v !== '');

            $where = "WHERE 1=1";

            if (!empty($search)) {
                $where .= " AND pekerjaan.nama_pekerjaan LIKE '%$search%'";
            }

            if (!empty($pekerjaan)) {
                $escaped = array_map(fn($v) => "'" . mysqli_real_escape_string($conn, $v) . "'", $pekerjaan);
                $in_list = implode(',', $escaped);
                $where .= " AND pekerjaan.nama_pekerjaan IN ($in_list)";
            }

            $query = mysqli_query($conn, "
                SELECT pekerjaan.*, categories.nama_categories
                FROM pekerjaan
                JOIN categories ON pekerjaan.categories_id = categories.id_categories
                $where
                LIMIT 12
            ");

            if (!$query) {
                die(mysqli_error($conn));
            }

            while ($data = mysqli_fetch_assoc($query)) {
            ?>
                <div class="pk-card">
                    <div class="pk-card-img dark">
                        <span class="pk-badge dark-badge">
                            <?php echo $data['nama_categories']; ?>
                        </span>
                        <img src="../Admin/gambar_pekerjaan/<?php echo $data['image']; ?>" alt="foto">
                    </div>
                    <div class="pk-card-body">
                        <h3><?php echo $data['nama_pekerjaan']; ?></h3>
                        <p><?php echo $data['deskripsi']; ?></p>
                    </div>
                    <div class="pk-card-footer">
                        <a href="detail.php?id=<?php echo $data['id_pekerjaan']; ?>" >View Detail</a>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </section>


    <footer>
        <p>&copy; 2024 Jelajah Karir. All rights reserved.</p>
    </footer>
</body>
</html>