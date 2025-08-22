<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/db.php';

$nis = $_GET['nis'] ?? '';
if (empty($nis)) {
    echo json_encode(["success" => false, "message" => "NIS kosong"]);
    exit;
}

$stmt = $db->prepare("
    SELECT s.*, p.tempat_pkl, p.mulai_pkl, p.selesai_pkl,
           p.pembimbing_perusahaan, p.no_hp_pembimbing_perusahaan,
           p.pembimbing_sekolah, p.no_hp_pembimbing_sekolah
    FROM biodata_siswa s
    LEFT JOIN biodata_pkl_siswa p ON s.nis = p.nis
    WHERE s.nis = ?
");
$stmt->bind_param("s", $nis);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "data" => $row
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Data tidak ditemukan"]);
}
