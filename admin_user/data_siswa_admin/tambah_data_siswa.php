<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/db.php';

$nis = $_POST['nis'] ?? '';
$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$jurusan = $_POST['jurusan'] ?? '';
$tempat_pkl = $_POST['tempat_pkl'] ?? '';
$mulai_pkl = $_POST['mulai_pkl'] ?? '';
$selesai_pkl = $_POST['selesai_pkl'] ?? '';
$pembimbing_perusahaan = $_POST['pembimbing_perusahaan'] ?? '';
$no_hp_pembimbing_perusahaan = $_POST['no_hp_pembimbing_perusahaan'] ?? '';
$pembimbing_sekolah = $_POST['pembimbing_sekolah'] ?? '';
$no_hp_pembimbing_sekolah = $_POST['no_hp_pembimbing_sekolah'] ?? '';
$foto_base64 = $_POST['foto'] ?? '';

// Validasi
if (
    empty($nis) || empty($nama_lengkap) || empty($jenis_kelamin) || empty($kelas) || 
    empty($jurusan) || empty($tempat_pkl) || empty($mulai_pkl) || empty($selesai_pkl) ||
    empty($pembimbing_perusahaan) || empty($no_hp_pembimbing_perusahaan) ||
    empty($pembimbing_sekolah) || empty($no_hp_pembimbing_sekolah) || empty($foto_base64)
) {
    echo json_encode(["success" => false, "message" => "Semua field wajib diisi"]);
    exit;
}

// Format tanggal
$mulai_pkl = date('Y-m-d', strtotime($mulai_pkl));
$selesai_pkl = date('Y-m-d', strtotime($selesai_pkl));

// Simpan foto
$foto_nama = $nis . ".jpg";
$upload_dir = __DIR__ . "/../../uploads/images_siswa/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
$foto_path = $upload_dir . $foto_nama;
file_put_contents($foto_path, base64_decode($foto_base64));

// Insert ke DB
$query1 = "INSERT INTO biodata_siswa (nis, nama_lengkap, jenis_kelamin, kelas, jurusan, foto)
           VALUES ('$nis', '$nama_lengkap', '$jenis_kelamin', '$kelas', '$jurusan', '$foto_nama')";

$query2 = "INSERT INTO biodata_pkl_siswa (nis, tempat_pkl, mulai_pkl, selesai_pkl, pembimbing_perusahaan, no_hp_pembimbing_perusahaan, pembimbing_sekolah, no_hp_pembimbing_sekolah)
           VALUES ('$nis', '$tempat_pkl', '$mulai_pkl', '$selesai_pkl', '$pembimbing_perusahaan', '$no_hp_pembimbing_perusahaan', '$pembimbing_sekolah', '$no_hp_pembimbing_sekolah')";

if (mysqli_query($db, $query1) && mysqli_query($db, $query2)) {
    echo json_encode(["success" => true, "message" => "Data berhasil ditambahkan"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan data: " . mysqli_error($db)]);
}

mysqli_close($db);
?>
