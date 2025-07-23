<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

$response = [
    'success' => false,
    'message' => '',
    'file_path' => ''
];

try {
    // 1. Validasi Request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metode request harus POST');
    }

    if (empty($_POST['nis'])) {
        throw new Exception('NIS siswa harus disertakan');
    }

    if (empty($_FILES['foto'])) {
        throw new Exception('File foto harus diupload');
    }

    $nis = $_POST['nis'];
    $file = $_FILES['foto'];

    // 2. Validasi File
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Format file tidak didukung. Hanya JPEG, JPG, dan PNG yang diperbolehkan');
    }

    if ($file['size'] > $maxFileSize) {
        throw new Exception('Ukuran file terlalu besar. Maksimal 2MB');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Terjadi kesalahan saat mengunggah file. Error code: ' . $file['error']);
    }

    // 3. Persiapkan Direktori Upload
    $uploadDir = __DIR__ . '/../../uploads/biodata_siswa/';
    
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('Gagal membuat direktori upload');
        }
    }

    // 4. Generate Nama File Unik
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'siswa_' . $nis . '_' . date('YmdHis') . '.' . $fileExt;
    $targetPath = $uploadDir . $filename;
    $relativePath = '/uploads/biodata_siswa/' . $filename;

    // 5. Pindahkan File ke Folder Uploads
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Gagal menyimpan file');
    }

    // 6. Update Database
    $stmt = $pdo->prepare("UPDATE siswa SET foto = ? WHERE nis = ?");
    if (!$stmt->execute([$relativePath, $nis])) {
        // Hapus file jika gagal update database
        unlink($targetPath);
        throw new Exception('Gagal memperbarui data foto di database');
    }

    // 7. Response Sukses
    $response = [
        'success' => true,
        'message' => 'Foto berhasil diupload',
        'file_path' => $relativePath
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>