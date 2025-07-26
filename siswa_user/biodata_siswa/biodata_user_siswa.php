<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../config/db.php';

// Set charset
mysqli_set_charset($db, 'utf8mb4');

// Check if NIS parameter exists
if (!isset($_GET['nis'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter NIS is required'
    ]);
    exit;
}

$nis = mysqli_real_escape_string($db, $_GET['nis']);

// Query to get student biodata
$query = "SELECT 
            bs.nis, 
            bs.nama_lengkap, 
            bs.kelas, 
            bs.jurusan,
            bs.tempat_lahir,
            DATE_FORMAT(bs.tanggal_lahir, '%d-%m-%Y') AS tanggal_lahir,
            bs.alamat_rumah,
            bs.no_hp,
            bp.tempat_pkl,
            bp.alamat_pkl,
            bp.bidang_kerja,
            bp.pembimbing,
            DATE_FORMAT(bp.mulai_pkl, '%d-%m-%Y') AS mulai_pkl,
            DATE_FORMAT(bp.selesai_pkl, '%d-%m-%Y') AS selesai_pkl,
            bp.status_pkl,
            bp.catatan_pkl
          FROM biodata_siswa bs
          LEFT JOIN biodata_pkl_siswa bp ON bs.nis = bp.nis
          WHERE bs.nis = '$nis'";

$result = mysqli_query($db, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . mysqli_error($db)
    ]);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
    $response = [
        'status' => 'success',
        'data' => [
            'nis' => $data['nis'],
            'nama_lengkap' => $data['nama_lengkap'],
            'kelas' => $data['kelas'],
            'jurusan' => $data['jurusan'],
            'tempat_lahir' => $data['tempat_lahir'] ?? '-',
            'tanggal_lahir' => $data['tanggal_lahir'] ?? '-',
            'alamat_rumah' => $data['alamat_rumah'] ?? '-',
            'no_hp' => $data['no_hp'] ?? '-',
            'tempat_pkl' => $data['tempat_pkl'] ?? 'Belum ada',
            'alamat_pkl' => $data['alamat_pkl'] ?? 'Belum ada',
            'bidang_kerja' => $data['bidang_kerja'] ?? 'Belum ada',
            'pembimbing' => $data['pembimbing'] ?? 'Belum ada',
            'mulai_pkl' => $data['mulai_pkl'] ?? 'Belum ada',
            'selesai_pkl' => $data['selesai_pkl'] ?? 'Belum ada',
            'status_pkl' => $data['status_pkl'] ?? 'Belum PKL',
            'catatan_pkl' => $data['catatan_pkl'] ?? 'Belum ada'
        ]
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Student data not found for NIS: ' . $nis
    ]);
}

mysqli_close($db);
?>