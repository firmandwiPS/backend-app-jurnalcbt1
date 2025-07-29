<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data['nis']) || !isset($data['key_akses']) || !isset($data['level'])) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit();
}

$nis = htmlspecialchars($data['nis']);
$key_akses = htmlspecialchars($data['key_akses']);
$level = htmlspecialchars($data['level']);

// Cek apakah NIS sudah terdaftar
$queryCheck = "SELECT * FROM login WHERE nis = ?";
$stmtCheck = mysqli_prepare($db, $queryCheck);
mysqli_stmt_bind_param($stmtCheck, "s", $nis);
mysqli_stmt_execute($stmtCheck);
$result = mysqli_stmt_get_result($stmtCheck);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(["success" => false, "message" => "NIS sudah terdaftar"]);
    exit();
}

// Insert data
$queryInsert = "INSERT INTO login (nis, key_akses, level) VALUES (?, ?, ?)";
$stmtInsert = mysqli_prepare($db, $queryInsert);
mysqli_stmt_bind_param($stmtInsert, "sss", $nis, $key_akses, $level);

if (mysqli_stmt_execute($stmtInsert)) {
    echo json_encode(["success" => true, "message" => "Data berhasil ditambahkan"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan data"]);
}

mysqli_stmt_close($stmtInsert);
mysqli_close($db);
?>
