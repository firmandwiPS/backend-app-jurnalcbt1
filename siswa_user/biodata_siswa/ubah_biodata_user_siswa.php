<?php
ob_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'aplikasi-jurnal-pkl-cbt1');
if (!$db) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}
mysqli_set_charset($db, 'utf8mb4');

// Get input data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate input
if (!$data || !isset($data['nis'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'NIS harus disertakan']);
    exit;
}

$nis = mysqli_real_escape_string($db, $data['nis']);

// Helper function to clean data
function cleanData($value) {
    return ($value === null || $value === '') ? null : trim($value);
}

// Helper function to validate date format
function isValidDate($date, $format = 'd-m-Y') {
    if (!$date) return false;
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

try {
    // Handle photo upload if provided
    $foto_name = null;
    if (!empty($data['foto_base64']) && strpos($data['foto_base64'], 'base64') !== false) {
        $foto_data = explode(',', $data['foto_base64']);
        if (count($foto_data) == 2) {
            $foto_content = base64_decode($foto_data[1]);
            if ($foto_content === false) {
                throw new Exception("Foto tidak valid");
            }

            $upload_dir = __DIR__ . '/uploads/foto_siswa/';
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    throw new Exception("Gagal membuat direktori upload");
                }
            }

            $foto_name = $nis . '_' . time() . '.jpg';
            $foto_path = $upload_dir . $foto_name;

            if (file_put_contents($foto_path, $foto_content) === false) {
                throw new Exception("Gagal menyimpan foto");
            }
        }
    }

    // Start transaction
    mysqli_begin_transaction($db);

    // Prepare and validate biodata
    $biodata_fields = array(
        'nama_lengkap' => cleanData($data['nama_lengkap'] ?? null),
        'jenis_kelamin' => cleanData($data['jenis_kelamin'] ?? null),
        'kelas' => cleanData($data['kelas'] ?? null),
        'jurusan' => cleanData($data['jurusan'] ?? null),
        'tempat_lahir' => cleanData($data['tempat_lahir'] ?? null),
        'tanggal_lahir' => cleanData($data['tanggal_lahir'] ?? null),
        'alamat_rumah' => cleanData($data['alamat_rumah'] ?? null),
        'no_hp' => cleanData($data['no_hp'] ?? null),
        'email' => cleanData($data['email'] ?? null),
        'status' => cleanData($data['status'] ?? 'aktif')
    );

    // Validate required fields
    if (empty($biodata_fields['nama_lengkap'])) {
        throw new Exception("Nama lengkap harus diisi");
    }
    if (empty($biodata_fields['jenis_kelamin'])) {
        throw new Exception("Jenis kelamin harus diisi");
    }
    if (empty($biodata_fields['kelas'])) {
        throw new Exception("Kelas harus diisi");
    }
    if (empty($biodata_fields['jurusan'])) {
        throw new Exception("Jurusan harus diisi");
    }

    // Validate date format if provided
    if ($biodata_fields['tanggal_lahir'] && !isValidDate($biodata_fields['tanggal_lahir'])) {
        throw new Exception("Format tanggal lahir tidak valid (harus dd-mm-yyyy)");
    }

    // Update biodata_siswa table
    $query1 = "UPDATE biodata_siswa SET 
        nama_lengkap = ?, 
        jenis_kelamin = ?, 
        kelas = ?, 
        jurusan = ?,
        tempat_lahir = ?, 
        tanggal_lahir = " . ($biodata_fields['tanggal_lahir'] ? "STR_TO_DATE(?, '%d-%m-%Y')" : "NULL") . ",
        alamat_rumah = ?, 
        no_hp = ?, 
        email = ?, 
        status = ?" 
        . ($foto_name ? ", foto = ?" : "") . " 
        WHERE nis = ?";

    $stmt1 = mysqli_prepare($db, $query1);
    if (!$stmt1) {
        throw new Exception("Gagal mempersiapkan query biodata: " . mysqli_error($db));
    }

    $params = array(
        $biodata_fields['nama_lengkap'],
        $biodata_fields['jenis_kelamin'],
        $biodata_fields['kelas'],
        $biodata_fields['jurusan'],
        $biodata_fields['tempat_lahir']
    );

    if ($biodata_fields['tanggal_lahir']) {
        $params[] = $biodata_fields['tanggal_lahir'];
    }

    $params = array_merge($params, array(
        $biodata_fields['alamat_rumah'],
        $biodata_fields['no_hp'],
        $biodata_fields['email'],
        $biodata_fields['status']
    ));

    if ($foto_name) {
        $params[] = $foto_name;
    }
    $params[] = $nis;

    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt1, $types, ...$params);

    if (!mysqli_stmt_execute($stmt1)) {
        throw new Exception("Gagal update biodata: " . mysqli_error($db));
    }

    // Handle PKL data
    $pkl_fields = array(
        'tempat_pkl' => cleanData($data['tempat_pkl'] ?? null),
        'alamat_pkl' => cleanData($data['alamat_pkl'] ?? null),
        'bidang_kerja' => cleanData($data['bidang_kerja'] ?? null),
        'pembimbing' => cleanData($data['pembimbing'] ?? null),
        'no_hp_pembimbing' => cleanData($data['no_hp_pembimbing'] ?? null),
        'mulai_pkl' => cleanData($data['mulai_pkl'] ?? null),
        'selesai_pkl' => cleanData($data['selesai_pkl'] ?? null),
        'status_pkl' => cleanData($data['status_pkl'] ?? 'berjalan'),
        'catatan_pkl' => cleanData($data['catatan_pkl'] ?? null)
    );

    // Validate PKL dates if provided
    if ($pkl_fields['mulai_pkl'] && !isValidDate($pkl_fields['mulai_pkl'])) {
        throw new Exception("Format tanggal mulai PKL tidak valid (harus dd-mm-yyyy)");
    }
    if ($pkl_fields['selesai_pkl'] && !isValidDate($pkl_fields['selesai_pkl'])) {
        throw new Exception("Format tanggal selesai PKL tidak valid (harus dd-mm-yyyy)");
    }

    // Check if any PKL data is provided
    $has_pkl_data = false;
    foreach ($pkl_fields as $value) {
        if ($value !== null) {
            $has_pkl_data = true;
            break;
        }
    }

    if ($has_pkl_data) {
        $query2 = "INSERT INTO biodata_pkl_siswa (
            nis, tempat_pkl, alamat_pkl, bidang_kerja, pembimbing,
            no_hp_pembimbing, mulai_pkl, selesai_pkl, status_pkl, catatan_pkl
        ) VALUES (?, ?, ?, ?, ?, ?, " 
            . ($pkl_fields['mulai_pkl'] ? "STR_TO_DATE(?, '%d-%m-%Y')" : "NULL") . ", " 
            . ($pkl_fields['selesai_pkl'] ? "STR_TO_DATE(?, '%d-%m-%Y')" : "NULL") . ", ?, ?)
        ON DUPLICATE KEY UPDATE
            tempat_pkl = VALUES(tempat_pkl),
            alamat_pkl = VALUES(alamat_pkl),
            bidang_kerja = VALUES(bidang_kerja),
            pembimbing = VALUES(pembimbing),
            no_hp_pembimbing = VALUES(no_hp_pembimbing),
            mulai_pkl = VALUES(mulai_pkl),
            selesai_pkl = VALUES(selesai_pkl),
            status_pkl = VALUES(status_pkl),
            catatan_pkl = VALUES(catatan_pkl),
            updated_at = CURRENT_TIMESTAMP";

        $stmt2 = mysqli_prepare($db, $query2);
        if (!$stmt2) {
            throw new Exception("Gagal mempersiapkan query PKL: " . mysqli_error($db));
        }

        $params = array(
            $nis,
            $pkl_fields['tempat_pkl'],
            $pkl_fields['alamat_pkl'],
            $pkl_fields['bidang_kerja'],
            $pkl_fields['pembimbing'],
            $pkl_fields['no_hp_pembimbing']
        );

        if ($pkl_fields['mulai_pkl']) {
            $params[] = $pkl_fields['mulai_pkl'];
        }
        if ($pkl_fields['selesai_pkl']) {
            $params[] = $pkl_fields['selesai_pkl'];
        }

        $params = array_merge($params, array(
            $pkl_fields['status_pkl'],
            $pkl_fields['catatan_pkl']
        ));

        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt2, $types, ...$params);

        if (!mysqli_stmt_execute($stmt2)) {
            throw new Exception("Gagal update data PKL: " . mysqli_error($db));
        }
    }

    // Commit transaction
    mysqli_commit($db);

    // Success response
    echo json_encode(array(
        'status' => 'success',
        'message' => 'Data berhasil diperbarui',
        'data' => array(
            'nis' => $nis,
            'foto_updated' => ($foto_name !== null)
        )
    ));

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($db);
    
    // Delete uploaded photo if transaction failed
    if (isset($foto_path)) {
        @unlink($foto_path);
    }
    
    http_response_code(500);
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
} finally {
    // Clean up
    if (isset($stmt1)) mysqli_stmt_close($stmt1);
    if (isset($stmt2)) mysqli_stmt_close($stmt2);
    mysqli_close($db);
    ob_end_flush();
}
?>