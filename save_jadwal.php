<?php
include "config/db.php";

// Cek admin
if (!isset($_REQUEST['admin']) || $_REQUEST['admin'] != 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Admin.");
}

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $paket = $_POST['paket'];
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    
    $query = "INSERT INTO jadwal (nama, paket, tanggal, jam) VALUES ('$nama', '$paket', '$tanggal', '$jam')";

    if (mysqli_query($conn, $query)) {
        header("Location: jadwal.php?admin=smart22studio");
        exit;
    } else {
        echo "Gagal menyimpan data. Error MySQL: " . mysqli_error($conn);
    }
}
?>