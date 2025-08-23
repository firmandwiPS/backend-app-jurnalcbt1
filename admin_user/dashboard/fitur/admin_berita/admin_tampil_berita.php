<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/db.php';

$response = array();

$query = "SELECT id_berita, judul_singkat, judul_lengkap, isi, kategori, tanggal FROM berita ORDER BY tanggal DESC";
$result = mysqli_query($db, $query);

if ($result) {
    $berita = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $berita[] = $row;
    }
    $response['status'] = 'success';
    $response['data'] = $berita;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Gagal ambil data: ' . mysqli_error($db);
}

echo json_encode($response);
?>
