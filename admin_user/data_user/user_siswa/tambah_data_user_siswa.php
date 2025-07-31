<?php
header('Content-Type: application/json');

// Include koneksi database
require_once __DIR__ . '/../../../config/db.php';

// Ambil data dari request JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (
    !isset($data['nis']) || empty($data['nis']) ||
    !isset($data['key_akses']) || empty($data['key_akses']) ||
    !isset($data['level']) || empty($data['level'])
) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit();
}

// Sanitasi input
$nis = htmlspecialchars(trim($data['nis']));
$key_akses = htmlspecialchars(trim($data['key_akses']));
$level = htmlspecialchars(trim($data['level']));

// Hanya izinkan level 5 (khusus siswa)
if ($level !== "5") {
    echo json_encode(["success" => false, "message" => "Level tidak valid untuk siswa"]);
    exit();
}

// Cek apakah NIS sudah terdaftar
$queryCheck = "SELECT id FROM login WHERE nis = ?";
$stmtCheck = mysqli_prepare($db, $queryCheck);
mysqli_stmt_bind_param($stmtCheck, "s", $nis);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);

if (mysqli_num_rows($resultCheck) > 0) {
    echo json_encode(["success" => false, "message" => "NIS sudah terdaftar"]);
    mysqli_stmt_close($stmtCheck);
    mysqli_close($db);
    exit();
}
mysqli_stmt_close($stmtCheck);

// Insert data siswa baru
$queryInsert = "INSERT INTO login (nis, key_akses, level) VALUES (?, ?, ?)";
$stmtInsert = mysqli_prepare($db, $queryInsert);
mysqli_stmt_bind_param($stmtInsert, "sss", $nis, $key_akses, $level);

if (mysqli_stmt_execute($stmtInsert)) {
    echo json_encode(["success" => true, "message" => "Data siswa berhasil ditambahkan"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan data siswa"]);
}

mysqli_stmt_close($stmtInsert);
mysqli_close($db);
?>
