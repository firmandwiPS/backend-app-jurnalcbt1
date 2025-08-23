<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include file konfigurasi database
require_once __DIR__ . '/../../../../config/db.php';

// Mendapatkan data input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Jika tidak ada data JSON, coba dari POST
if (empty($data)) {
    $data = $_POST;
}

if (!isset($data['id_berita']) || empty($data['id_berita'])) {
    echo json_encode(array("status" => "error", "message" => "ID berita tidak ditemukan"));
    exit;
}

$id_berita = mysqli_real_escape_string($db, $data['id_berita']);

try {
    // Pertama, dapatkan nama file gambar untuk dihapus dari server
    $query = "SELECT gambar FROM berita WHERE id_berita = '$id_berita'";
    $result = mysqli_query($db, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Decode JSON gambar jika ada
        if (!empty($row['gambar'])) {
            $gambar_data = json_decode($row['gambar'], true);
            
            // Hapus file gambar dari server jika ada
            if (is_array($gambar_data)) {
                foreach ($gambar_data as $gambar) {
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/backend-app-jurnalcbt1/uploads/berita/" . $gambar;
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            }
        }
    }
    
    // Hapus data dari database
    $query = "DELETE FROM berita WHERE id_berita = '$id_berita'";
    $result = mysqli_query($db, $query);
    
    if ($result) {
        echo json_encode(array("status" => "success", "message" => "Berita berhasil dihapus"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Gagal menghapus berita: " . mysqli_error($db)));
    }
} catch (Exception $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}

// Tutup koneksi database
mysqli_close($db);
?>