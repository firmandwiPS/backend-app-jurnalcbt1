<?php
header('Content-Type: application/json');

// ==== KONEKSI DB ====

header('Content-Type: application/json');

// Koneksi ke database
require_once __DIR__ . '/../../config/db.php';


$nis = $_GET['nis'] ?? '';
if (empty($nis)) {
    echo json_encode(["success" => false, "message" => "NIS tidak valid"]);
    exit;
}

// ==== QUERY DATA SISWA + PKL ====
$query = "SELECT bs.*, bp.tempat_pkl, bp.mulai_pkl, bp.selesai_pkl, 
                 bp.pembimbing_perusahaan, bp.no_hp_pembimbing_perusahaan,
                 bp.pembimbing_sekolah, bp.no_hp_pembimbing_sekolah
          FROM biodata_siswa bs
          LEFT JOIN biodata_pkl_siswa bp ON bs.nis = bp.nis
          WHERE bs.nis = '$nis' LIMIT 1";

$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(["success" => false, "message" => "Data siswa tidak ditemukan"]);
    exit;
}

$data = mysqli_fetch_assoc($result);

// ==== FORMAT TANGGAL ====
if (!empty($data['mulai_pkl'])) {
    $data['mulai_pkl'] = date('d F Y', strtotime($data['mulai_pkl']));
}
if (!empty($data['selesai_pkl'])) {
    $data['selesai_pkl'] = date('d F Y', strtotime($data['selesai_pkl']));
}

// ==== GREETING ====
// Set timezone ke WIB
date_default_timezone_set("Asia/Jakarta");

$hour = date('H');
if ($hour >= 4 && $hour < 11) {
    $greeting = "Selamat Pagi";
} elseif ($hour >= 11 && $hour < 15) {
    $greeting = "Selamat Siang";
} elseif ($hour >= 15 && $hour < 18) {
    $greeting = "Selamat Sore";
} else {
    $greeting = "Selamat Malam";
}

// ==== CURRENT DATE ====
$currentDateTime = date('l, d F Y, H:i:s');

// ==== FOTO SISWA ====
// Path folder di server
$uploadDir = "C:/laragon/www/backend-app-jurnalcbt1/uploads/images_siswa/";

// Gunakan IP PC di jaringan, bukan localhost!
$baseUrl = "http://192.168.1.103/backend-app-jurnalcbt1/uploads/images_siswa/";

// 1. Ambil dari kolom DB kalau ada
$fotoFile = $data['foto'] ?? null;

// 2. Kalau kosong, cari berdasarkan NIS
if (empty($fotoFile)) {
    $extensions = ['jpg','jpeg','png'];
    foreach ($extensions as $ext) {
        if (file_exists($uploadDir . $nis . "." . $ext)) {
            $fotoFile = $nis . "." . $ext;
            break;
        }
    }
}

// 3. Build URL
$fotoUrl = $fotoFile ? $baseUrl . $fotoFile : null;

// ==== RESPONSE ====
$response = [
    "success" => true,
    "greeting" => $greeting,
    "nama_lengkap" => $data['nama_lengkap'],
    "kelas" => $data['kelas'],
    "jurusan" => $data['jurusan'],
    "currentDateTime" => $currentDateTime,
    "pkl_data" => [
        "tempat_pkl" => $data['tempat_pkl'],
        "periode" => $data['mulai_pkl'] . " - " . $data['selesai_pkl'],
        "pembimbing_perusahaan" => $data['pembimbing_perusahaan'],
        "pembimbing_sekolah" => $data['pembimbing_sekolah']
    ],
    "foto_url" => $fotoUrl // <--- Glide akan load ini
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
