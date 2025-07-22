<?php
header('Content-Type: application/json');

// Koneksi ke database
require_once __DIR__ . '/../config/db.php';

// Cek koneksi
if (!$db) {
    echo json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . mysqli_connect_error(),
        'data' => []
    ]);
    exit;
}

// Tangkap parameter NIS (jika ada)
$nis = isset($_GET['nis']) ? mysqli_real_escape_string($db, $_GET['nis']) : null;

// Query ambil data
$query = "SELECT * FROM biodata_siswa";
if ($nis) {
    $query .= " WHERE nis = '$nis'";
}

$result = mysqli_query($db, $query);

// Cek hasil
if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Query error: ' . mysqli_error($db),
        'data' => []
    ]);
    exit;
}

// Ambil dan susun data
$data = [];
$base_url = "http://192.168.1.110/backend-app-jurnalcbt1/uploads/"; // Ganti sesuai URL hosting kamu

while ($row = mysqli_fetch_assoc($result)) {
    $foto = $row['foto'] ? $base_url . $row['foto'] : $base_url . 'default.jpg'; // fallback foto default

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
            'foto' => $foto
    ];
}

// Output JSON
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
