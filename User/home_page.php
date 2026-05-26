<?php
session_start();
include_once '../Include/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home_page.css">
    <title>Jelajah karir</title>
</head>
<body>
    <nav> 
        <img src="../Gambar/Nobg.png" alt="logo" class="logo">
        <ul class="navbar">
            <li><a href="#home_page">Home</a></li>
            <li><a href="#categories">Categories</a></li>
            <li><a href="#pekerjaan">Pekerjaan</a></li>
            <li class="nav-kanan"><a href="../Login/Login.php">Login</a></li>
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
            <a href="#categories" class="btn-primary">Mulai Eksplorasi &rarr;</a>
        </div>
        <div class="deco-navy"></div>    
        <div class="hero-image">
            <img src="../Gambar/Grafis_orang_login.png" alt="Grafis">
        </div>
    </section>
    
    <section class="categories" id="categories">
        <h1>Categories</h1>
        <form method="GET" action="#pekerjaan">
        <div class="categories-container">

          <div class="card tech">
            <div class="icon-circle">
                <img src="../Icon/Teknoologi.png" alt="Teknologi">
            </div>

            <span>Teknologi & Teknik</span>
            <select name="pekerjaan[]">
              <option value="">Cari</option>
              <?php
              $q = mysqli_query($conn, "SELECT * FROM pekerjaan WHERE categories_id='1'");
              while($d = mysqli_fetch_assoc($q)) {
                  $sel = in_array($d['nama_pekerjaan'], $_GET['pekerjaan'] ?? []) ? 'selected' : '';
                  echo "<option value='".htmlspecialchars($d['nama_pekerjaan'], ENT_QUOTES)."' $sel>".$d['nama_pekerjaan']."</option>";
              }
              ?>
            </select>
          </div>

          <div class="card health">
            <div class="icon-circle">
                <img src="../Icon/Kesehatan.png" alt="Kesehatan">
            </div>

            <span>Kesehatan & Sosial</span>
            <select name="pekerjaan[]">
              <option value="">Cari</option>
              <?php
              $q = mysqli_query($conn, "SELECT * FROM pekerjaan WHERE categories_id='2'");
              while($d = mysqli_fetch_assoc($q)) {
                  $sel = in_array($d['nama_pekerjaan'], $_GET['pekerjaan'] ?? []) ? 'selected' : '';
                  echo "<option value='".htmlspecialchars($d['nama_pekerjaan'], ENT_QUOTES)."' $sel>".$d['nama_pekerjaan']."</option>";
              }
              ?>
            </select>
          </div>

          <div class="card business">
            <div class="icon-circle">
                <img src="../Icon/Bisnis.png" alt="Bisnis">
            </div>

            <span>Bisnis & Oprasional</span>
            <select name="pekerjaan[]">
              <option value="">Cari</option>
              <?php
              $q = mysqli_query($conn, "SELECT * FROM pekerjaan WHERE categories_id='3'");
              while($d = mysqli_fetch_assoc($q)) {
                  $sel = in_array($d['nama_pekerjaan'], $_GET['pekerjaan'] ?? []) ? 'selected' : '';
                  echo "<option value='".htmlspecialchars($d['nama_pekerjaan'], ENT_QUOTES)."' $sel>".$d['nama_pekerjaan']."</option>";
              }
              ?>
            </select>
          </div>

          <div class="card law">
            <div class="icon-circle">
                <img src="../Icon/Hukum.png" alt="Hukum">
            </div>

            <span>Hukum & Keamanan</span>
            <select name="pekerjaan[]">
              <option value="">Cari</option>
              <?php
              $q = mysqli_query($conn, "SELECT * FROM pekerjaan WHERE categories_id='8'");
              while($d = mysqli_fetch_assoc($q)) {
                  $sel = in_array($d['nama_pekerjaan'], $_GET['pekerjaan'] ?? []) ? 'selected' : '';
                  echo "<option value='".htmlspecialchars($d['nama_pekerjaan'], ENT_QUOTES)."' $sel>".$d['nama_pekerjaan']."</option>";
              }
              ?>
            </select>
          </div>
        
          <div class="card creative">
            <div class="icon-circle">
                <img src="../Icon/Kreatif.png" alt="Kreatif">
            </div>
            <span>Kreatif & Pariwisata</span>
            <select name="pekerjaan[]">
              <option value="">Cari</option>
              <?php
              $q = mysqli_query($conn, "SELECT * FROM pekerjaan WHERE categories_id='11'");
              while($d = mysqli_fetch_assoc($q)) {
                  $sel = in_array($d['nama_pekerjaan'], $_GET['pekerjaan'] ?? []) ? 'selected' : '';
                  echo "<option value='".htmlspecialchars($d['nama_pekerjaan'], ENT_QUOTES)."' $sel>".$d['nama_pekerjaan']."</option>";
              }
              ?>
            </select>
          </div>

          <div class="card education">
             <div class="icon-circle">
                <img src="../Icon/Pendidikan.png" alt="Pendidikan">
            </div>
            <span>Pendidikan & Alam</span>
            <select name="pekerjaan[]">
              <option value="">Cari</option>
              <?php
              $q = mysqli_query($conn, "SELECT * FROM pekerjaan WHERE categories_id='12'");
              while($d = mysqli_fetch_assoc($q)) {
                  $sel = in_array($d['nama_pekerjaan'], $_GET['pekerjaan'] ?? []) ? 'selected' : '';
                  echo "<option value='".htmlspecialchars($d['nama_pekerjaan'], ENT_QUOTES)."' $sel>".$d['nama_pekerjaan']."</option>";
              }
              ?>
            </select>
          </div>
        
         
        </div>
        <button type="submit" class="btn-cari">Cari</button>
        </form>
    </section>

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