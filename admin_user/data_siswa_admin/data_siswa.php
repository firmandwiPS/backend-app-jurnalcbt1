<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Koneksi database
require_once __DIR__ . '/../../config/db.php';

// Base URL untuk akses foto
$base_url = "http://192.168.1.103/backend-app-jurnalcbt1/uploads/images_siswa/";

// Lokasi folder foto di server (local path)
$upload_dir = "C:/laragon/www/backend-app-jurnalcbt1/uploads/images_siswa/";

// Query gabungan biodata siswa + PKL
$sql = "SELECT 
            b.nis,
            b.nama_lengkap,
            p.tempat_pkl,
            p.mulai_pkl,
            p.selesai_pkl,
            p.pembimbing_perusahaan,
            p.no_hp_pembimbing_perusahaan,
            p.pembimbing_sekolah,
            p.no_hp_pembimbing_sekolah
        FROM biodata_siswa b
        LEFT JOIN biodata_pkl_siswa p 
            ON b.nis = p.nis
        ORDER BY b.nama_lengkap ASC";

$result = mysqli_query($db, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Query gagal: " . mysqli_error($db)
    ]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Format tanggal jadi yyyy-mm-dd
    if (!empty($row['mulai_pkl']) && $row['mulai_pkl'] != "0000-00-00") {
        $row['mulai_pkl'] = date('Y-m-d', strtotime($row['mulai_pkl']));
    }
    if (!empty($row['selesai_pkl']) && $row['selesai_pkl'] != "0000-00-00") {
        $row['selesai_pkl'] = date('Y-m-d', strtotime($row['selesai_pkl']));
    }

    // Cek foto siswa berdasarkan NIS
    $nis = $row['nis'];
    $foto_path = $upload_dir . $nis . ".jpg";
    if (file_exists($foto_path)) {
        $row['foto'] = $nis . ".jpg";
    } else {
        $row['foto'] = "default.jpg";
    }

    $data[] = $row;
}

// Output JSON
echo json_encode([
    "success" => true,
    "data" => $data
], JSON_PRETTY_PRINT);

mysqli_close($db);
