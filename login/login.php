<?php
header('Content-Type: application/json');

// Include koneksi ke database
require_once '../config/db.php';

// Ambil key akses dari input POST
$key_akses = isset($_POST['key_akses']) ? $_POST['key_akses'] : '';

if (empty($key_akses)) {
    echo json_encode(["success" => false, "message" => "Key akses wajib diisi"]);
    exit();
}

// Cek key_akses di database
$stmt = mysqli_prepare($db, "SELECT * FROM login WHERE key_akses = ?");
mysqli_stmt_bind_param($stmt, "s", $key_akses);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    echo json_encode([
        "success" => true,
        "level" => $user['level'],
        "nis" => $user['nis'],
        "id" => $user['id']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Key akses tidak valid"]);
}

mysqli_stmt_close($stmt);
mysqli_close($db);
?>
