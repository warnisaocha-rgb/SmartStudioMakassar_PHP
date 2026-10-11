<?php
include "config/db.php";

// Cek admin
if (!isset($_GET['admin']) || $_GET['admin'] != 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Admin.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM jadwal WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: jadwal.php?admin=smart22studio");
        exit;
    }
}
?>