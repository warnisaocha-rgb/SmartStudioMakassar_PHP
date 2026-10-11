<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/sync_paket.php';

// Cek admin
$adminKey = $_POST['admin'] ?? $_GET['admin'] ?? '';
if ($adminKey !== 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Administrator.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'self-photo');
    $harga = (int)($_POST['harga'] ?? 0);
    $durasi = trim($_POST['durasi'] ?? '');
    $kapasitas = trim($_POST['kapasitas'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $rekomendasi = isset($_POST['rekomendasi']) ? 1 : 0;
    $rawFasilitas = trim($_POST['fasilitas'] ?? '');
    $gambarLama = trim($_POST['gambar_lama'] ?? 'assets/self-foto.jpg');

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

    $gambarPath = $gambarLama;
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

    $sql = "UPDATE paket SET 
            nama = '$s_nama',
            kategori = '$s_kategori',
            kategori_label = '$s_kategoriLabel',
            harga = $harga,
            harga_format = '$s_hargaFormat',
            durasi = '$s_durasi',
            kapasitas = '$s_kapasitas',
            deskripsi = '$s_deskripsi',
            fasilitas = '$s_fasilitas',
            tag = '$s_tag',
            rekomendasi = $rekomendasi,
            gambar = '$s_gambar'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        syncPaketJson($conn);
        header("Location: kelola_paket.php?admin=smart22studio&pesan=sukses_edit");
        exit;
    } else {
        die("Gagal memperbarui paket: " . mysqli_error($conn));
    }
} else {
    header("Location: kelola_paket.php?admin=smart22studio");
    exit;
}
