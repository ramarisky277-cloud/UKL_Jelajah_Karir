<?php
session_start();
include_once '../Include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id_users, password, role FROM users WHERE username = ?");
    
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id_users, $hashed_password, $role);
    $stmt->fetch();
    $stmt->close();

    if ($hashed_password && password_verify($password, $hashed_password)) {
        $_SESSION['id_users'] = $id_users;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if (strtolower($role) == 'admin') {
            header('Location: ../Admin/Dashboard.php');
        } else {
            header('Location: ../User/home_page.php');
        }
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Login.css">
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
            <h1>Login</h1>
            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="Login.php" method="post">
            <br>Username:<br><input type="text" name="username" placeholder="Enter your username">
            Password:<br>
            <input type="password" name="password" placeholder="password" required><br>
            <button type="submit">Login</button>
            </form>
              <p>Belum punya akun? <a href="../Register/Register.php">Register di sini</a>.</p>
        </div>
    </div>
</body>
</html>