<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/db.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        $input = $_POST; // fallback to form data
    }

    $nis = $input['nis'] ?? '';
    $nama_lengkap = $input['nama_lengkap'] ?? '';
    $jurusan = $input['jurusan'] ?? '';
    $kelas = $input['kelas'] ?? '';
    $tempat_lahir = $input['tempat_lahir'] ?? '';
    $tanggal_lahir = $input['tanggal_lahir'] ?? '';
    $alamat_rumah = $input['alamat_rumah'] ?? '';
    $no_hp = $input['no_hp'] ?? '';
    $tempat_pkl = $input['tempat_pkl'] ?? '';
    $alamat_pkl = $input['alamat_pkl'] ?? '';
    $bidang_kerja = $input['bidang_kerja'] ?? '';
    $pembimbing = $input['pembimbing'] ?? '';
    $mulai_pkl = $input['mulai_pkl'] ?? '';
    $selesai_pkl = $input['selesai_pkl'] ?? '';
    $status_pkl = $input['status_pkl'] ?? '';
    $catatan_pkl = $input['catatan_pkl'] ?? '';

    // Validate required fields
    if (empty($nis) || empty($nama_lengkap) || empty($no_hp) || empty($alamat_rumah)) {
        $response['message'] = 'NIS, Nama Lengkap, No HP, dan Alamat Rumah wajib diisi';
        echo json_encode($response);
        exit;
    }

    try {
        // Prepare update statement
        $stmt = $db->prepare("UPDATE biodata_siswa SET 
            nama_lengkap = ?,
            jurusan = ?,
            kelas = ?,
            tempat_lahir = ?,
            tanggal_lahir = ?,
            alamat_rumah = ?,
            no_hp = ?,
            tempat_pkl = ?,
            alamat_pkl = ?,
            bidang_kerja = ?,
            pembimbing = ?,
            mulai_pkl = ?,
            selesai_pkl = ?,
            status_pkl = ?,
            catatan_pkl = ?
            WHERE nis = ?");

        // Execute the statement
        $result = $stmt->execute([
            $nama_lengkap,
            $jurusan,
            $kelas,
            $tempat_lahir,
            $tanggal_lahir,
            $alamat_rumah,
            $no_hp,
            $tempat_pkl,
            $alamat_pkl,
            $bidang_kerja,
            $pembimbing,
            $mulai_pkl,
            $selesai_pkl,
            $status_pkl,
            $catatan_pkl,
            $nis
        ]);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Biodata berhasil diperbarui';
        } else {
            $response['message'] = 'Gagal memperbarui biodata: ' . $stmt->error;
        }
    } catch (Exception $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>n