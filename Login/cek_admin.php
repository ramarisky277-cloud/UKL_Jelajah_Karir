<?php
include_once '../Include/koneksi.php';

$password_baru = 'Admin123'; // bebas ganti sesuai keinginan
$hash = password_hash($password_baru, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $hash);

if ($stmt->execute()) {
    echo "✅ Password berhasil direset!<br>";
    echo "Username: admin<br>";
    echo "Password baru: " . $password_baru;
} else {
    echo "❌ Gagal: " . $conn->error;
}
$stmt->close();
?>