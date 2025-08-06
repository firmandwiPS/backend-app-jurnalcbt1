<?php
header('Content-Type: application/json');

// Koneksi ke database
require_once __DIR__ . '/../../../config/db.php';

// Ambil data dari JSON input
$input = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (isset($input["id"])) {
    $id = mysqli_real_escape_string($db, $input["id"]);

    // Query hapus
    $query = "DELETE FROM login WHERE id='$id'";

    if (mysqli_query($db, $query)) {
        echo json_encode(["success" => true, "message" => "Databerhasil dihapus"]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($db)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID tidak ditemukan"]);
}
?>
