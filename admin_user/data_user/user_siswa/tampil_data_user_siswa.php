<?php
header('Content-Type: application/json');

// Koneksi ke database
require_once __DIR__ . '/../../../config/db.php';

$response = [];

$query = "SELECT id, nis, key_akses, level FROM login WHERE level = '5'";
$result = mysqli_query($db, $query);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal query: " . mysqli_error($db)
    ]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    "success" => true,
    "message" => "Data siswa ditemukan",
    "data" => $data
]);
?>
