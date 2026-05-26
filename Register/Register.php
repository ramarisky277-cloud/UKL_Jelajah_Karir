<?php
ob_start(); // ← TAMBAH INI — mencegah masalah "headers already sent"
session_start();
include_once '../Include/koneksi.php';

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Tampilkan semua error SQL


$error = '';
$success = '';
$username_input = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $username_input = $username; // Simpan untuk ditampilkan kembali di form

    // Validasi Input
    if (empty($username)) {
        $error = "Username tidak boleh kosong.";
    } elseif (strlen($username) < 3) {
        $error = "Username minimal 3 karakter.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error = "Username hanya boleh mengandung huruf, angka, dan underscore.";
    } elseif (empty($password)) {
        $error = "Password tidak boleh kosong.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $sql_check = "SELECT id_users FROM users WHERE username = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);

        if (!$stmt_check) {
            $error = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $error = "Username ini sudah terdaftar.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $sql_register = "INSERT INTO users (username, password, role) VALUES (?, ?, 'user')";
                $stmt = mysqli_prepare($conn, $sql_register);

                if (!$stmt) {
                    $error = "Database error: " . mysqli_error($conn);
                } else {
                    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        mysqli_stmt_close($stmt_check);
                        ob_end_clean(); // ← Bersihkan buffer sebelum redirect
                        header("Location: ../Login/Login.php");
                        exit();
                    } else {
                        $error = "Gagal registrasi: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            mysqli_stmt_close($stmt_check);
        }
    }
}
ob_end_flush(); // ← Kirim buffer ke browser
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Register.css">
    <title>Jelajah karir</title>
</head>
<body>
    <div class="regis-container">
        <div class="bagian_kiri">
            <div class="gambar_logo">
             <img src="../Gambar/Nobg.png"  width="10%" alt="Logo">
            </div>
            <div class="grafis_regis">
             <img src="../Gambar/Grafis_orang_login.png"  alt="Grafis">
            </div>
         </div>
        <div class="form_regis">
            <h1>Register</h1>
              <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div style="color: green; margin-bottom: 10px;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form action="Register.php" method="post">
            <br>Username:<br><input type="text" name="username" placeholder="Enter your username">
            Password:<br>
            <input type="password" name="password" placeholder="password" required>
            Konfirmasi password:<br>
            <input type="password" name="confirm_password" placeholder="Konfirmasi password" required><br>
            <button type="submit">Submit</button>
            </form>
              <p>Belum punya akun? <a href="../Login/Login.php">Login di sini</a>.</p>
        </div>
    </div>
</body>
</html>