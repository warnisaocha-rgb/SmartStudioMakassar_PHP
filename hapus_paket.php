<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/sync_paket.php';

// Cek admin
if (!isset($_GET['admin']) || $_GET['admin'] !== 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Administrator.");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM paket WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        syncPaketJson($conn);
        header("Location: kelola_paket.php?admin=smart22studio&pesan=sukses_hapus");
        exit;
    } else {
        die("Gagal menghapus paket: " . mysqli_error($conn));
    }
} else {
    header("Location: kelola_paket.php?admin=smart22studio");
    exit;
}
