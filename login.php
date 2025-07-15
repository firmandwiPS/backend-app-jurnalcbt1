<?php
header('Content-Type: application/json');

// Menggunakan file koneksi
require_once 'config/db.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Username dan password wajib diisi"]);
    exit();
}

// Gunakan koneksi dari db.php ($db)
$stmt = mysqli_prepare($db, "SELECT * FROM akun WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "success" => true,
            "level" => $user['level'],
            "nama" => $user['nama'],
            "id_akun" => $user['id_akun']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Password salah"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User tidak ditemukan"]);
}

mysqli_stmt_close($stmt);
mysqli_close($db);
?>
