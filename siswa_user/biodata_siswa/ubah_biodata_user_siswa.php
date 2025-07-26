<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");

require_once __DIR__ . '/../../config/db.php';

// Set charset
mysqli_set_charset($db, "utf8mb4");

// Get POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validate NIS
if (!isset($data['nis'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "NIS is required"]);
    exit;
}

$nis = mysqli_real_escape_string($db, $data['nis']);

// Initialize response
$response = [
    "status" => "success",
    "messages" => [],
    "affected_rows" => [
        "biodata" => 0,
        "pkl" => 0
    ]
];

// Start transaction
mysqli_begin_transaction($db);

try {
    // Update biodata_siswa if there are fields to update
    if (isset($data['biodata']) && is_array($data['biodata'])) {
        $biodataUpdates = [];
        $allowedBiodataFields = [
            'nama_lengkap', 'kelas', 'jurusan', 
            'tempat_lahir', 'tanggal_lahir', 
            'alamat_rumah', 'no_hp'
        ];
        
        foreach ($allowedBiodataFields as $field) {
            if (isset($data['biodata'][$field])) {
                $value = $data['biodata'][$field] !== null ? 
                    "'" . mysqli_real_escape_string($db, $data['biodata'][$field]) . "'" : "NULL";
                $biodataUpdates[] = "$field = $value";
            }
        }
        
        if (!empty($biodataUpdates)) {
            $query = "UPDATE biodata_siswa SET " . implode(', ', $biodataUpdates) . " WHERE nis = '$nis'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Error updating biodata: " . mysqli_error($db));
            }
            $response['affected_rows']['biodata'] = mysqli_affected_rows($db);
            $response['messages'][] = "Biodata updated successfully";
        }
    }

    // Update biodata_pkl_siswa if there are fields to update
    if (isset($data['pkl']) && is_array($data['pkl'])) {
        $pklUpdates = [];
        $allowedPklFields = [
            'tempat_pkl', 'alamat_pkl', 'bidang_kerja',
            'pembimbing', 'mulai_pkl', 'selesai_pkl',
            'status_pkl', 'catatan_pkl'
        ];
        
        foreach ($allowedPklFields as $field) {
            if (isset($data['pkl'][$field])) {
                $value = $data['pkl'][$field] !== null ? 
                    "'" . mysqli_real_escape_string($db, $data['pkl'][$field]) . "'" : "NULL";
                $pklUpdates[] = "$field = $value";
            }
        }
        
        if (!empty($pklUpdates)) {
            $query = "UPDATE biodata_pkl_siswa SET " . implode(', ', $pklUpdates) . " WHERE nis = '$nis'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Error updating PKL data: " . mysqli_error($db));
            }
            $response['affected_rows']['pkl'] = mysqli_affected_rows($db);
            $response['messages'][] = "PKL data updated successfully";
        }
    }

    // Commit transaction if all queries succeeded
    mysqli_commit($db);
    
    // If no updates were made to either table
    if ($response['affected_rows']['biodata'] == 0 && $response['affected_rows']['pkl'] == 0) {
        $response['messages'][] = "No changes were made (data already up-to-date)";
    }

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($db);
    http_response_code(500);
    $response = [
        "status" => "error",
        "message" => $e->getMessage()
    ];
}

echo json_encode($response);
mysqli_close($db);
?>