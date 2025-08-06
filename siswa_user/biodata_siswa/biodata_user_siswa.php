<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Database configuration
$db = mysqli_connect('localhost', 'root', '', 'aplikasi-jurnal-pkl-cbt1');

if (!$db) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Set charset
mysqli_set_charset($db, 'utf8mb4');

// Check parameter
if (!isset($_GET['nis']) || empty(trim($_GET['nis']))) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter NIS is required'
    ]);
    exit;
}

$nis = mysqli_real_escape_string($db, trim($_GET['nis']));

// Query with prepared statement
$query = "SELECT 
            bs.nis, 
            bs.nama_lengkap, 
            bs.jenis_kelamin,
            bs.kelas, 
            bs.jurusan,
            bs.tempat_lahir,
            DATE_FORMAT(bs.tanggal_lahir, '%d-%m-%Y') AS tanggal_lahir,
            bs.alamat_rumah,
            bs.no_hp,
            bs.email,
            bs.foto,
            bs.status,
            bp.tempat_pkl,
            bp.alamat_pkl,
            bp.bidang_kerja,
            bp.pembimbing,
            bp.no_hp_pembimbing,
            DATE_FORMAT(bp.mulai_pkl, '%d-%m-%Y') AS mulai_pkl,
            DATE_FORMAT(bp.selesai_pkl, '%d-%m-%Y') AS selesai_pkl,
            bp.status_pkl,
            bp.catatan_pkl
          FROM biodata_siswa bs
          LEFT JOIN biodata_pkl_siswa bp ON bs.nis = bp.nis
          WHERE bs.nis = ?";

$stmt = mysqli_prepare($db, $query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database prepare error'
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $nis);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database query error'
    ]);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    // Path configuration
    $local_path = __DIR__ . '/uploads/foto_siswa/';
    $default_foto_path = __DIR__ . '/uploads/default.png';

    // Determine the photo path
    $foto_file = $data['foto'] ?? null;
    $foto_path = file_exists($local_path . $foto_file) ? $local_path . $foto_file : $default_foto_path;

    // Get photo content and convert to base64
    try {
        $foto_content = file_get_contents($foto_path);
        $foto_info = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $foto_info->buffer($foto_content);
        $foto_base64 = 'data:' . $mime_type . ';base64,' . base64_encode($foto_content);
    } catch (Exception $e) {
        $foto_base64 = null;
    }

    $response = [
        'status' => 'success',
        'data' => [
            'nis' => $data['nis'] ?? '-',
            'nama_lengkap' => $data['nama_lengkap'] ?? '-',
            'jenis_kelamin' => $data['jenis_kelamin'] ?? '-', // Will show 'L' or 'P' directly
            'kelas' => $data['kelas'] ?? '-',
            'jurusan' => $data['jurusan'] ?? '-',
            'tempat_lahir' => $data['tempat_lahir'] ?? '-',
            'tanggal_lahir' => $data['tanggal_lahir'] ?? '-',
            'alamat_rumah' => $data['alamat_rumah'] ?? '-',
            'no_hp' => $data['no_hp'] ?? '-',
            'email' => $data['email'] ?? '-',
            'status' => $data['status'] ?? '-',
            'foto_base64' => $foto_base64,

            // PKL data
            'tempat_pkl' => $data['tempat_pkl'] ?? null,
            'alamat_pkl' => $data['alamat_pkl'] ?? null,
            'bidang_kerja' => $data['bidang_kerja'] ?? null,
            'pembimbing' => $data['pembimbing'] ?? null,
            'no_hp_pembimbing' => $data['no_hp_pembimbing'] ?? null,
            'mulai_pkl' => $data['mulai_pkl'] ?? null,
            'selesai_pkl' => $data['selesai_pkl'] ?? null,
            'status_pkl' => $data['status_pkl'] ?? null,
            'catatan_pkl' => $data['catatan_pkl'] ?? null
        ]
    ];

    // Clean null values from response
    $response['data'] = array_filter($response['data'], function($value) {
        return $value !== null;
    });

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak ditemukan untuk NIS: ' . $nis
    ]);
}

// Clean up
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
mysqli_close($db);
?>