<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all input data
    $nis = $_POST['nis'] ?? '';
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $jurusan = $_POST['jurusan'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $alamat_rumah = $_POST['alamat_rumah'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $tempat_pkl = $_POST['tempat_pkl'] ?? '';
    $pembimbing = $_POST['pembimbing'] ?? '';
    $mulai_pkl = $_POST['mulai_pkl'] ?? '';
    $selesai_pkl = $_POST['selesai_pkl'] ?? '';
    $status_pkl = $_POST['status_pkl'] ?? '';
    $catatan_pkl = $_POST['catatan_pkl'] ?? '';
    $foto = $_POST['foto'] ?? '';

    // Validate required fields
    if (empty($nis) || empty($nama_lengkap) || empty($no_hp) || empty($alamat_rumah)) {
        $response['message'] = 'Data wajib tidak boleh kosong';
        echo json_encode($response);
        exit;
    }

    try {
        // Prepare update statement
        $stmt = $pdo->prepare("UPDATE siswa SET 
            nama_lengkap = :nama_lengkap,
            jurusan = :jurusan,
            kelas = :kelas,
            tempat_lahir = :tempat_lahir,
            tanggal_lahir = :tanggal_lahir,
            alamat_rumah = :alamat_rumah,
            no_hp = :no_hp,
            tempat_pkl = :tempat_pkl,
            pembimbing = :pembimbing,
            mulai_pkl = :mulai_pkl,
            selesai_pkl = :selesai_pkl,
            status_pkl = :status_pkl,
            catatan_pkl = :catatan_pkl,
            foto = :foto
            WHERE nis = :nis");

        // Bind parameters
        $stmt->bindParam(':nama_lengkap', $nama_lengkap);
        $stmt->bindParam(':jurusan', $jurusan);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->bindParam(':tempat_lahir', $tempat_lahir);
        $stmt->bindParam(':tanggal_lahir', $tanggal_lahir);
        $stmt->bindParam(':alamat_rumah', $alamat_rumah);
        $stmt->bindParam(':no_hp', $no_hp);
        $stmt->bindParam(':tempat_pkl', $tempat_pkl);
        $stmt->bindParam(':pembimbing', $pembimbing);
        $stmt->bindParam(':mulai_pkl', $mulai_pkl);
        $stmt->bindParam(':selesai_pkl', $selesai_pkl);
        $stmt->bindParam(':status_pkl', $status_pkl);
        $stmt->bindParam(':catatan_pkl', $catatan_pkl);
        $stmt->bindParam(':foto', $foto);
        $stmt->bindParam(':nis', $nis);

        // Execute the statement
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Biodata berhasil diperbarui';
        } else {
            $response['message'] = 'Gagal memperbarui biodata';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>