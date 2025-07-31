<?php
header('Content-Type: application/json');

// Koneksi ke database
require_once __DIR__ . '/../../../config/db.php';

// Ambil data dari JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (isset($input["id"], $input["nis"], $input["key_akses"], $input["level"])) {
    $id = mysqli_real_escape_string($db, $input["id"]);
    $nis = mysqli_real_escape_string($db, $input["nis"]);
    $key_akses = mysqli_real_escape_string($db, $input["key_akses"]);
    $level = mysqli_real_escape_string($db, $input["level"]);

    // Query update
    $query = "UPDATE login SET nis='$nis', key_akses='$key_akses', level='$level' WHERE id='$id'";

    if (mysqli_query($db, $query)) {
        echo json_encode(["success" => true, "message" => "Data berhasil diubah"]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($db)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Parameter tidak lengkap"]);
}
?>
