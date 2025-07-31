<?php
header('Content-Type: application/json');

// Koneksi database
require_once __DIR__ . '/../../../config/db.php';

// Ambil data input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($input["id"], $input["nis"], $input["key_akses"], $input["level"])) {
    echo json_encode(["success" => false, "message" => "Parameter tidak lengkap"]);
    exit();
}

// Ambil dan sanitasi data
$id = mysqli_real_escape_string($db, trim($input["id"]));
$nis = mysqli_real_escape_string($db, trim($input["nis"]));
$key_akses = mysqli_real_escape_string($db, trim($input["key_akses"]));
$level = mysqli_real_escape_string($db, trim($input["level"]));

// Validasi khusus siswa
if ($level !== "5") {
    echo json_encode(["success" => false, "message" => "Level tidak valid untuk siswa"]);
    exit();
}

// Cek apakah NIS yang baru sudah dipakai oleh ID lain
$cekNis = "SELECT id FROM login WHERE nis = ? AND id != ?";
$stmtCheck = mysqli_prepare($db, $cekNis);
mysqli_stmt_bind_param($stmtCheck, "ss", $nis, $id);
mysqli_stmt_execute($stmtCheck);
$resCheck = mysqli_stmt_get_result($stmtCheck);

if (mysqli_num_rows($resCheck) > 0) {
    echo json_encode(["success" => false, "message" => "NIS sudah digunakan oleh user lain"]);
    mysqli_stmt_close($stmtCheck);
    mysqli_close($db);
    exit();
}
mysqli_stmt_close($stmtCheck);

// Proses update data siswa
$query = "UPDATE login SET nis = ?, key_akses = ?, level = ? WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ssss", $nis, $key_akses, $level, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true, "message" => "Data siswa berhasil diubah"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal mengubah data siswa"]);
}

mysqli_stmt_close($stmt);
mysqli_close($db);
?>
