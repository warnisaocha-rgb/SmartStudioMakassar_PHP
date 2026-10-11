<?php
// Sinkronisasi data paket ke file JSON
function syncPaketJson($conn) {
    $query = mysqli_query($conn, "SELECT * FROM paket ORDER BY id DESC");
    if (!$query) {
        return false;
    }

    $items = [];
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

    $jsonPath = dirname(__DIR__) . '/data/paket.json';
    return (bool) file_put_contents($jsonPath, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}
