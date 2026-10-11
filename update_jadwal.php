<?php
include "config/db.php";

// Cek admin
if (!isset($_REQUEST['admin']) || $_REQUEST['admin'] != 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Admin.");
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $paket = $_POST['paket'];
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];

    $query = "UPDATE jadwal SET nama = '$nama', paket = '$paket', tanggal = '$tanggal', jam = '$jam' WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: jadwal.php?admin=smart22studio");
        exit;
    } else {
        echo "Gagal mengedit data: " . mysqli_error($conn);
    }
}
?>