<?php
header('Content-Type: application/json');

// Koneksi ke database
require_once __DIR__ . '/../../config/db.php';

// Cek koneksi
if (!$db) {
    echo json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . mysqli_connect_error(),
        'data' => []
    ]);
    exit;
}

// Tangkap parameter NIS
$nis = isset($_GET['nis']) ? mysqli_real_escape_string($db, $_GET['nis']) : null;

if (!$nis) {
    echo json_encode([
        'success' => false,
        'message' => 'NIS tidak diberikan',
        'data' => []
    ]);
    exit;
}

// Query ambil data siswa
$query = "SELECT * FROM biodata_siswa WHERE nis = '$nis'";
$result = mysqli_query($db, $query);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Query error: ' . mysqli_error($db),
        'data' => []
    ]);
    exit;
}

$data = [];
$base_url = "http://192.168.1.4/backend-app-jurnalcbt1/uploads/";
$upload_dir = __DIR__ . '/../uploads/';

while ($row = mysqli_fetch_assoc($result)) {
    $foto_nama = $row['foto'] ?? '';
    $foto_path = $upload_dir . $foto_nama;

    $foto_url = (file_exists($foto_path) && !empty($foto_nama))
        ? $base_url . $foto_nama
        : $base_url . 'default.jpg';

    $data[] = [
        'nis' => $row['nis'],
        'nama_lengkap' => $row['nama_lengkap'],
        'kelas' => $row['kelas'],
        'jurusan' => $row['jurusan'],
        'tempat_lahir' => $row['tempat_lahir'],
        'tanggal_lahir' => $row['tanggal_lahir'],
        'alamat_rumah' => $row['alamat_rumah'],
        'no_hp' => $row['no_hp'],
        'tempat_pkl' => $row['tempat_pkl'],
        'alamat_pkl' => $row['alamat_pkl'],
        'bidang_kerja' => $row['bidang_kerja'],
        'pembimbing' => $row['pembimbing'],
        'mulai_pkl' => $row['mulai_pkl'],
        'selesai_pkl' => $row['selesai_pkl'],
        'status_pkl' => $row['status_pkl'],
        'catatan_pkl' => $row['catatan_pkl'],
        'foto' => $foto_url
    ];
}

if (count($data) > 0) {
    echo json_encode([
        'success' => true,
        'message' => 'Data ditemukan',
        'data' => $data
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak ditemukan',
        'data' => []
    ]);
}
