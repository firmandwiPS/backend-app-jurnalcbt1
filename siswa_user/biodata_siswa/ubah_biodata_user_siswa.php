<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/../../config/db.php';
mysqli_set_charset($db, "utf8mb4");

if (!isset($_POST['nis']) || empty($_POST['nis'])) {
    echo json_encode(["status" => "error", "message" => "NIS tidak ditemukan"]);
    exit;
}

$nis = mysqli_real_escape_string($db, $_POST['nis']);
$response = [
    "status" => "success",
    "messages" => [],
    "affected_rows" => ["biodata" => 0, "pkl" => 0]
];

$biodata = $_POST['biodata'] ?? [];
$pkl = $_POST['pkl'] ?? [];

mysqli_begin_transaction($db);

try {
    // Update Biodata
    $biodataFields = ['nama_lengkap', 'kelas', 'jurusan', 'tempat_lahir', 'tanggal_lahir', 'alamat_rumah', 'no_hp'];
    $biodataSet = [];

    foreach ($biodataFields as $field) {
        if (isset($biodata[$field])) {
            $val = mysqli_real_escape_string($db, $biodata[$field]);
            $biodataSet[] = "`$field` = '$val'";
        }
    }

    if (!empty($biodataSet)) {
        $sql = "UPDATE biodata_siswa SET " . implode(", ", $biodataSet) . " WHERE nis = '$nis'";
        if (!mysqli_query($db, $sql)) {
            throw new Exception("Gagal update biodata: " . mysqli_error($db));
        }
        $response['affected_rows']['biodata'] = mysqli_affected_rows($db);
        $response['messages'][] = "Biodata berhasil diperbarui.";
    }

    // Update/Insert PKL
    $pklFields = ['tempat_pkl', 'alamat_pkl', 'bidang_kerja', 'pembimbing', 'mulai_pkl', 'selesai_pkl', 'status_pkl', 'catatan_pkl'];
    $pklData = [];

    foreach ($pklFields as $field) {
        if (isset($pkl[$field])) {
            $val = mysqli_real_escape_string($db, $pkl[$field]);
            $pklData[$field] = $val;
        }
    }

    if (!empty($pklData)) {
        $cek = mysqli_query($db, "SELECT id FROM biodata_pkl_siswa WHERE nis = '$nis'");
        if (!$cek) {
            throw new Exception("Gagal cek PKL: " . mysqli_error($db));
        }

        if (mysqli_num_rows($cek) > 0) {
            // Update PKL
            $set = [];
            foreach ($pklData as $key => $val) {
                $set[] = "`$key` = '$val'";
            }
            $sql = "UPDATE biodata_pkl_siswa SET " . implode(", ", $set) . " WHERE nis = '$nis'";
        } else {
            // Insert PKL
            $cols = array_merge(['nis'], array_keys($pklData));
            $vals = array_merge(["'$nis'"], array_map(fn($v) => "'$v'", array_values($pklData)));
            $sql = "INSERT INTO biodata_pkl_siswa (" . implode(",", $cols) . ") VALUES (" . implode(",", $vals) . ")";
        }

        if (!mysqli_query($db, $sql)) {
            throw new Exception("Gagal simpan PKL: " . mysqli_error($db));
        }

        $response['affected_rows']['pkl'] = mysqli_affected_rows($db);
        $response['messages'][] = "Data PKL berhasil disimpan.";
    }

    mysqli_commit($db);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    mysqli_rollback($db);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
