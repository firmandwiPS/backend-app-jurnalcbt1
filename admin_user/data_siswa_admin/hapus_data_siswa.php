<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Koneksi ke database
require_once __DIR__ . '/../../config/db.php';

// Ambil data NIS dari POST atau GET
$nis = isset($_POST['nis']) ? $_POST['nis'] : (isset($_GET['nis']) ? $_GET['nis'] : '');

if (empty($nis)) {
    echo json_encode([
        "success" => false,
        "message" => "Parameter NIS tidak ditemukan"
    ]);
    exit;
}

// Path folder foto siswa
$upload_dir = __DIR__ . "/../../uploads/images_siswa/";
$foto_path = $upload_dir . $nis . ".jpg";

// Hapus foto jika ada
if (file_exists($foto_path)) {
    unlink($foto_path);
}

// Hapus data siswa di tabel PKL (jika ada relasi)
mysqli_query($db, "DELETE FROM biodata_pkl_siswa WHERE nis = '$nis'");

// Hapus data siswa di tabel biodata siswa
$query = mysqli_query($db, "DELETE FROM biodata_siswa WHERE nis = '$nis'");

if ($query) {
    echo json_encode([
        "success" => true,
        "message" => "Data siswa dengan NIS $nis berhasil dihapus"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menghapus data siswa: " . mysqli_error($db)
    ]);
}

mysqli_close($db);
?>
