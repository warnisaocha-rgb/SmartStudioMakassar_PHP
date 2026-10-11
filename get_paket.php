<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/config/db.php';

$sql = "SELECT * FROM paket ORDER BY id DESC";
$query = mysqli_query($conn, $sql);

$items = [];

if ($query && mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $fasilitas = [];
        if (!empty($row['fasilitas'])) {
            $decoded = json_decode($row['fasilitas'], true);
            if (is_array($decoded)) {
                $fasilitas = $decoded;
            } else {
                $lines = explode("\n", str_replace("\r", "", $row['fasilitas']));
                foreach ($lines as $line) {
                    $trimmed = trim($line);
                    if ($trimmed !== '') {
                        $fasilitas[] = $trimmed;
                    }
                }
            }
        }

        $items[] = [
            'id' => $row['kode_paket'],
            'nama' => $row['nama'],
            'kategori' => $row['kategori'],
            'kategoriLabel' => $row['kategori_label'],
            'harga' => (int)$row['harga'],
            'hargaFormat' => $row['harga_format'],
            'durasi' => $row['durasi'],
            'kapasitas' => $row['kapasitas'],
            'deskripsi' => $row['deskripsi'],
            'fasilitas' => $fasilitas,
            'tag' => $row['tag'] ?? '',
            'rekomendasi' => (bool)$row['rekomendasi'],
            'gambar' => $row['gambar']
        ];
    }
} else {
    // Fallback JSON lokal jika database kosong
    $jsonPath = __DIR__ . '/data/paket.json';
    if (file_exists($jsonPath)) {
        $fallbackData = json_decode(file_get_contents($jsonPath), true);
        if (is_array($fallbackData)) {
            $items = $fallbackData;
        }
    }
}

echo json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
