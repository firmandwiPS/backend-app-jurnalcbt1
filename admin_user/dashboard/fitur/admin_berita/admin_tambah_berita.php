<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_singkat = mysqli_real_escape_string($db, $_POST['judul_singkat']);
    $judul_lengkap = mysqli_real_escape_string($db, $_POST['judul_lengkap']);
    $isi           = mysqli_real_escape_string($db, $_POST['isi']);
    $kategori      = mysqli_real_escape_string($db, $_POST['kategori']);
    $gambarList    = isset($_POST['gambar_list']) ? $_POST['gambar_list'] : '[]';

    $decodedImages = json_decode($gambarList, true);
    $savedPaths = [];

    if (is_array($decodedImages)) {
        foreach ($decodedImages as $key => $base64Image) {
            // Generate nama file unik
            $fileName = "berita_" . time() . "_$key.jpg";
            $uploadDir = __DIR__ . "/../../../../uploads/images_berita/";
            
            // Buat direktori jika belum ada
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filePath = $uploadDir . $fileName;
            
            // Simpan gambar
            if (file_put_contents($filePath, base64_decode($base64Image))) {
                $savedPaths[] = "uploads/images_berita/" . $fileName;
            }
        }
    }

    $gambarJson = json_encode($savedPaths);
    $tanggal = date('Y-m-d H:i:s');

    $query = "INSERT INTO berita (judul_singkat, judul_lengkap, isi, kategori, gambar, tanggal) 
              VALUES ('$judul_singkat', '$judul_lengkap', '$isi', '$kategori', '$gambarJson', '$tanggal')";

    if (mysqli_query($db, $query)) {
        $response['status'] = 'success';
        $response['message'] = 'Berita berhasil ditambahkan';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Gagal tambah berita: ' . mysqli_error($db);
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode tidak valid';
}

echo json_encode($response);
?>