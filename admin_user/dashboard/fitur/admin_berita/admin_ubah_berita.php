<?php


$db = mysqli_connect('localhost', 'root', '', 'aplikasi-jurnal-pkl-cbt1');

if (!$db) {
    die("Koneksi database gagal: " . mysqli_connect_error());
} else {
    echo "Koneksi database berhasil!";
}