<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/db.php';

$nis = $_GET['nis'] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(empty($nis)){
        echo json_encode(['success'=>false, 'message'=>'NIS tidak boleh kosong']);
        exit;
    }

    $query = mysqli_query($db, "
        SELECT s.nis, s.nama_lengkap, s.jenis_kelamin, s.kelas, s.jurusan, s.foto,
               p.tempat_pkl, p.mulai_pkl, p.selesai_pkl, p.pembimbing_perusahaan, 
               p.no_hp_pembimbing_perusahaan, p.pembimbing_sekolah, p.no_hp_pembimbing_sekolah
        FROM biodata_siswa s
        LEFT JOIN biodata_pkl_siswa p ON s.nis = p.nis
        WHERE s.nis='$nis'
    ");

    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);
        echo json_encode(['success'=>true, 'data'=>$data]);
    } else {
        echo json_encode(['success'=>false, 'message'=>'Data tidak ditemukan']);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nis = $_POST['nis'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $tempat_pkl = $_POST['tempat_pkl'];
    $mulai_pkl = $_POST['mulai_pkl'];
    $selesai_pkl = $_POST['selesai_pkl'];
    $pembimbing_perusahaan = $_POST['pembimbing_perusahaan'];
    $no_hp_pembimbing_perusahaan = $_POST['no_hp_pembimbing_perusahaan'];
    $pembimbing_sekolah = $_POST['pembimbing_sekolah'];
    $no_hp_pembimbing_sekolah = $_POST['no_hp_pembimbing_sekolah'];
    $foto_base64 = $_POST['foto'] ?? '';

    // simpan foto jika ada
    if(!empty($foto_base64)){
        $foto_data = base64_decode($foto_base64);
        $foto_name = "siswa_".$nis."_".time().".jpg";
        file_put_contents("../uploads/images_siswa/$foto_name", $foto_data);
        $foto_query = ", foto='$foto_name'";
    } else {
        $foto_query = "";
    }

    $update_siswa = mysqli_query($db, "
        UPDATE biodata_siswa SET 
            nama_lengkap='$nama_lengkap',
            jenis_kelamin='$jenis_kelamin',
            kelas='$kelas',
            jurusan='$jurusan'
            $foto_query
        WHERE nis='$nis'
    ");

    // cek apakah ada data PKL
    $check_pkl = mysqli_query($db, "SELECT nis FROM biodata_pkl_siswa WHERE nis='$nis'");
    if(mysqli_num_rows($check_pkl) > 0){
        $update_pkl = mysqli_query($db, "
            UPDATE biodata_pkl_siswa SET
                tempat_pkl='$tempat_pkl',
                mulai_pkl='$mulai_pkl',
                selesai_pkl='$selesai_pkl',
                pembimbing_perusahaan='$pembimbing_perusahaan',
                no_hp_pembimbing_perusahaan='$no_hp_pembimbing_perusahaan',
                pembimbing_sekolah='$pembimbing_sekolah',
                no_hp_pembimbing_sekolah='$no_hp_pembimbing_sekolah'
            WHERE nis='$nis'
        ");
    } else {
        $insert_pkl = mysqli_query($db, "
            INSERT INTO biodata_pkl_siswa
            (nis, tempat_pkl, mulai_pkl, selesai_pkl, pembimbing_perusahaan, no_hp_pembimbing_perusahaan, pembimbing_sekolah, no_hp_pembimbing_sekolah)
            VALUES
            ('$nis','$tempat_pkl','$mulai_pkl','$selesai_pkl','$pembimbing_perusahaan','$no_hp_pembimbing_perusahaan','$pembimbing_sekolah','$no_hp_pembimbing_sekolah')
        ");
    }

    if($update_siswa){
        echo json_encode(['success'=>true, 'message'=>'Data berhasil diubah']);
    } else {
        echo json_encode(['success'=>false, 'message'=>'Gagal mengubah data']);
    }
}
?>
