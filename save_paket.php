<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/sync_paket.php';

// Cek admin
$adminKey = $_POST['admin'] ?? $_GET['admin'] ?? '';
if ($adminKey !== 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Administrator.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'self-photo');
    $harga = (int)($_POST['harga'] ?? 0);
    $durasi = trim($_POST['durasi'] ?? '');
    $kapasitas = trim($_POST['kapasitas'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $rekomendasi = isset($_POST['rekomendasi']) ? 1 : 0;
    $rawFasilitas = trim($_POST['fasilitas'] ?? '');

    // Format harga
    $hargaFormat = "Rp " . number_format($harga, 0, ',', '.');
    if (!empty($_POST['harga_format'])) {
        $hargaFormat = trim($_POST['harga_format']);
    }


    $kategoriLabels = [
        'self-photo' => 'Self Photo',
        'wisuda' => 'Wisuda',
        'formal' => 'Pas Foto, Nikah & Ijazah',
        'keluarga' => 'Foto Keluarga',
        'group' => 'Group & Rombongan'
    ];
    $kategoriLabel = $kategoriLabels[$kategori] ?? ucfirst($kategori);

    // Format fasilitas ke JSON
    $fasilitasList = [];
    if (!empty($rawFasilitas)) {
        $lines = explode("\n", str_replace("\r", "", $rawFasilitas));
        foreach ($lines as $line) {
            $cleaned = trim($line);
            if ($cleaned !== '') {
                $fasilitasList[] = $cleaned;
            }
        }
    }
    $fasilitasJson = json_encode($fasilitasList, JSON_UNESCAPED_UNICODE);


    $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nama));
    $slugBase = trim($slugBase, '-');
    if (empty($slugBase)) {
        $slugBase = 'paket-' . time();
    }
    $kodePaket = $slugBase;
    $cekSlug = mysqli_query($conn, "SELECT id FROM paket WHERE kode_paket = '$kodePaket'");
    if ($cekSlug && mysqli_num_rows($cekSlug) > 0) {
        $kodePaket .= '-' . time();
    }

    // Upload gambar
    $gambarPath = 'assets/self-foto.jpg';
    if (!empty($_POST['gambar_pilihan'])) {
        $gambarPath = trim($_POST['gambar_pilihan']);
    }


    if (!empty($_POST['gambar_cropped']) && strpos($_POST['gambar_cropped'], 'data:image') === 0) {
        $dataUri = $_POST['gambar_cropped'];
        if (preg_match('/^data:image\/\w+;base64,(.+)$/', $dataUri, $matches)) {
            $imageData = base64_decode($matches[1]);
            if ($imageData !== false && strlen($imageData) > 100) {
                $newFileName = 'paket-' . time() . '-' . rand(100, 999) . '.jpg';
                $destination = __DIR__ . '/assets/' . $newFileName;
                if (file_put_contents($destination, $imageData)) {
                    $gambarPath = 'assets/' . $newFileName;
                }
            }
        }
    } 

    elseif (isset($_FILES['gambar_upload']) && $_FILES['gambar_upload']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['gambar_upload']['tmp_name'];
        $fileName = $_FILES['gambar_upload']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($fileExt, $allowed)) {
            $newFileName = 'paket-' . time() . '-' . rand(100, 999) . '.' . $fileExt;
            $destination = __DIR__ . '/assets/' . $newFileName;
            if (move_uploaded_file($fileTmp, $destination)) {
                $gambarPath = 'assets/' . $newFileName;
            }
        }
    }


    $s_kode = mysqli_real_escape_string($conn, $kodePaket);
    $s_nama = mysqli_real_escape_string($conn, $nama);
    $s_kategori = mysqli_real_escape_string($conn, $kategori);
    $s_kategoriLabel = mysqli_real_escape_string($conn, $kategoriLabel);
    $s_hargaFormat = mysqli_real_escape_string($conn, $hargaFormat);
    $s_durasi = mysqli_real_escape_string($conn, $durasi);
    $s_kapasitas = mysqli_real_escape_string($conn, $kapasitas);
    $s_deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $s_fasilitas = mysqli_real_escape_string($conn, $fasilitasJson);
    $s_tag = mysqli_real_escape_string($conn, $tag);
    $s_gambar = mysqli_real_escape_string($conn, $gambarPath);

    $sql = "INSERT INTO paket (kode_paket, nama, kategori, kategori_label, harga, harga_format, durasi, kapasitas, deskripsi, fasilitas, tag, rekomendasi, gambar) 
            VALUES ('$s_kode', '$s_nama', '$s_kategori', '$s_kategoriLabel', $harga, '$s_hargaFormat', '$s_durasi', '$s_kapasitas', '$s_deskripsi', '$s_fasilitas', '$s_tag', $rekomendasi, '$s_gambar')";

    if (mysqli_query($conn, $sql)) {
        syncPaketJson($conn);
        header("Location: kelola_paket.php?admin=smart22studio&pesan=sukses_tambah");
        exit;
    } else {
        die("Gagal menambahkan paket: " . mysqli_error($conn));
    }
} else {
    header("Location: kelola_paket.php?admin=smart22studio");
    exit;
}
