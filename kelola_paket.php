<?php
require_once __DIR__ . '/config/db.php';

// Cek admin
if (!isset($_GET['admin']) || $_GET['admin'] !== 'smart22studio') {
    die("Akses ditolak! Halaman ini dikhususkan untuk Administrator Smart Studio.");
}

// Pesan status
$pesan = $_GET['pesan'] ?? '';
$alertHtml = '';
if ($pesan === 'sukses_tambah') {
    $alertHtml = '<div class="alert-box alert-success">Paket layanan berhasil ditambahkan ke basis data dan katalog publik.</div>';
} elseif ($pesan === 'sukses_edit') {
    $alertHtml = '<div class="alert-box alert-success">Perubahan data paket berhasil disimpan dan diperbarui.</div>';
} elseif ($pesan === 'sukses_hapus') {
    $alertHtml = '<div class="alert-box alert-warning">Paket layanan telah berhasil dihapus dari sistem.</div>';
}

// Ambil data paket
$queryPaket = mysqli_query($conn, "SELECT * FROM paket ORDER BY id DESC");
$totalPaket = mysqli_num_rows($queryPaket);

// Ambil file gambar di folder assets
$assetImages = [];
$assetsDir = __DIR__ . '/assets';
if (is_dir($assetsDir)) {
    $files = scandir($assetsDir);
    foreach ($files as $file) {
        if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'])) {
            $assetImages[] = 'assets/' . $file;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Paket Foto & Layanan | Smart Studio Makassar</title>
  <link rel="icon" type="image/png" href="assets/logo.png">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
  <style>
    body {
      background-color: #F8F9FA;
      color: #112250;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 30px 20px 80px 20px;
    }


    .admin-header-nav {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-radius: 8px;
      padding: 16px 24px;
      margin-bottom: 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      box-shadow: 0 2px 8px rgba(17, 34, 80, 0.04);
    }

    .admin-brand-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .admin-badge-indicator {
      display: inline-block;
      font-size: 0.72rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 700;
      background: #112250;
      color: #FFFFFF;
      padding: 4px 10px;
      border-radius: 4px;
    }

    .admin-brand-title {
      font-size: 1rem;
      font-weight: 600;
      color: #112250;
    }

    .admin-nav-links {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .admin-nav-btn {
      display: inline-flex;
      align-items: center;
      padding: 8px 18px;
      font-size: 0.88rem;
      font-weight: 600;
      text-decoration: none;
      border-radius: 6px;
      border: 1px solid #CBD5E1;
      background: #FFFFFF;
      color: #334155;
      transition: all 0.2s ease;
    }

    .admin-nav-btn:hover {
      background: #F1F5F9;
      color: #0F172A;
      border-color: #94A3B8;
    }

    .admin-nav-btn.active {
      background: #112250;
      color: #FFFFFF;
      border-color: #112250;
    }

    .admin-nav-btn.preview-btn {
      background: #F8FAFC;
      border-color: #E2E8F0;
      color: #475569;
    }


    .page-title-box {
      margin-bottom: 28px;
    }

    .page-heading {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 2.2rem;
      font-weight: 700;
      color: #112250;
      line-height: 1.2;
      margin-bottom: 6px;
    }

    .page-subheading {
      font-size: 0.95rem;
      color: #64748B;
    }


    .alert-box {
      padding: 14px 20px;
      border-radius: 6px;
      font-size: 0.9rem;
      font-weight: 500;
      margin-bottom: 24px;
    }

    .alert-success {
      background: #ECFDF5;
      border: 1px solid #A7F3D0;
      color: #065F46;
    }

    .alert-warning {
      background: #FFFBEB;
      border: 1px solid #FDE68A;
      color: #92400E;
    }


    .panel-card {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-radius: 8px;
      padding: 28px;
      margin-bottom: 32px;
      box-shadow: 0 4px 14px rgba(17, 34, 80, 0.04);
    }

    .panel-header {
      border-bottom: 1px solid #F1F5F9;
      padding-bottom: 16px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .panel-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: #112250;
      margin: 0;
    }

    .panel-meta {
      font-size: 0.85rem;
      color: #64748B;
    }


    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .form-grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-label {
      font-size: 0.85rem;
      font-weight: 600;
      color: #1E293B;
    }

    .form-label .required {
      color: #DC2626;
      margin-left: 2px;
    }

    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #CBD5E1;
      border-radius: 6px;
      font-size: 0.9rem;
      font-family: inherit;
      background: #FFFFFF;
      color: #0F172A;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
      outline: none;
      border-color: #112250;
      box-shadow: 0 0 0 3px rgba(17, 34, 80, 0.08);
    }

    .form-textarea {
      resize: vertical;
      min-height: 90px;
      line-height: 1.5;
    }

    .form-hint {
      font-size: 0.78rem;
      color: #64748B;
      margin-top: 2px;
    }

    .checkbox-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 0.88rem;
      font-weight: 500;
      color: #1E293B;
      cursor: pointer;
      user-select: none;
    }

    .checkbox-label input[type="checkbox"] {
      width: 17px;
      height: 17px;
      accent-color: #112250;
      cursor: pointer;
    }

    .btn-submit-action {
      background: #112250;
      color: #FFFFFF;
      border: 1px solid #112250;
      border-radius: 6px;
      padding: 12px 28px;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s ease, border-color 0.2s ease;
    }

    .btn-submit-action:hover {
      background: #0A1533;
      border-color: #0A1533;
    }


    .table-responsive {
      overflow-x: auto;
      border-radius: 6px;
      border: 1px solid #E2E8F0;
    }

    .modern-table {
      width: 100%;
      border-collapse: collapse;
      background: #FFFFFF;
      font-size: 0.88rem;
    }

    .modern-table th {
      background: #F8FAFC;
      color: #475569;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.74rem;
      letter-spacing: 0.6px;
      padding: 14px 16px;
      border-bottom: 1px solid #E2E8F0;
      text-align: left;
    }

    .modern-table td {
      padding: 14px 16px;
      border-bottom: 1px solid #F1F5F9;
      color: #1E293B;
      vertical-align: middle;
    }

    .modern-table tr:last-child td {
      border-bottom: none;
    }

    .modern-table tr:hover td {
      background: #F8FAFC;
    }

    .thumb-preview {
      width: 60px;
      height: 48px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid #E2E8F0;
    }

    .category-badge {
      display: inline-block;
      font-size: 0.76rem;
      font-weight: 600;
      padding: 3px 8px;
      border-radius: 4px;
      background: #EEF2F7;
      color: #112250;
    }

    .badge-rekomendasi {
      display: inline-block;
      font-size: 0.72rem;
      font-weight: 600;
      padding: 2px 7px;
      border-radius: 4px;
      background: #FEF3C7;
      color: #92400E;
      border: 1px solid #FDE68A;
    }

    .action-links {
      display: flex;
      gap: 6px;
      align-items: center;
    }

    .btn-edit-link {
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: 600;
      text-decoration: none;
      background: #F8FAFC;
      border: 1px solid #CBD5E1;
      color: #334155;
      transition: all 0.15s ease;
    }

    .btn-edit-link:hover {
      background: #E2E8F0;
      color: #0F172A;
    }

    .btn-delete-link {
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: 600;
      text-decoration: none;
      background: #FEF2F2;
      border: 1px solid #FECACA;
      color: #991B1B;
      transition: all 0.15s ease;
    }

    .btn-delete-link:hover {
      background: #FEE2E2;
      color: #7F1D1D;
    }

    @media (max-width: 768px) {
      .form-grid, .form-grid-3 {
        grid-template-columns: 1fr;
      }
      .admin-header-nav {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>

  <!-- Header Navigasi -->
  <header class="site-header">
    <div class="container nav-wrap">
      <a href="index.html" class="logo-brand">
        <img src="assets/emblem_trans.png" alt="Smart Studio Logo" class="brand-logo-img">
        <div class="brand-logo-text">
          <span class="logo-title">SMART <span>STUDIO</span></span>
          <span class="logo-sub">MAKASSAR</span>
        </div>
      </a>
    </div>
  </header>

  <main class="admin-container">

    
    <div class="admin-header-nav">
      <div class="admin-brand-info">
        <span class="admin-badge-indicator">Administrator</span>
        <span class="admin-brand-title">Panel Manajemen Studio</span>
      </div>
      <nav class="admin-nav-links">
        <a href="jadwal.php?admin=smart22studio" class="admin-nav-btn">Jadwal Pemotretan</a>
        <a href="kelola_paket.php?admin=smart22studio" class="admin-nav-btn active">Katalog Paket Foto</a>
        <a href="paket.html" class="admin-nav-btn preview-btn" target="_blank">Lihat Halaman Pengguna &rarr;</a>
      </nav>
    </div>

    
    <div class="page-title-box" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
      <div>
        <h1 class="page-heading">Kelola Katalog Paket Foto</h1>
      </div>
      <div>
        <button type="button" id="btn-toggle-tambah" class="btn-submit-action" style="padding: 10px 22px; font-size: 0.88rem; cursor: pointer;">
          + Tambah Paket Baru
        </button>
      </div>
    </div>

    <?php echo $alertHtml; ?>

    <!-- Form Tambah Paket -->
    <section class="panel-card" id="form-tambah-panel" style="display: none; margin-bottom: 28px;">
      <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h2 class="panel-title">Tambah Paket Layanan Baru</h2>
        </div>
        <button type="button" id="btn-tutup-form" style="background: none; border: none; color: #64748B; font-size: 0.85rem; font-weight: 600; cursor: pointer; padding: 4px 8px;">Tutup Formulir &times;</button>
      </div>

      <form action="save_paket.php?admin=smart22studio" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="admin" value="smart22studio">

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Paket Layanan <span class="required">*</span></label>
            <input type="text" name="nama" class="form-input" placeholder="Contoh: Paket Foto Wisuda Premium" required>
          </div>

          <div class="form-group">
            <label class="form-label">Kategori Layanan <span class="required">*</span></label>
            <select name="kategori" class="form-select" required>
              <option value="self-photo">Self Photo</option>
              <option value="wisuda">Wisuda</option>
              <option value="formal">Pas Foto, Nikah &amp; Ijazah</option>
              <option value="keluarga">Foto Keluarga</option>
              <option value="group">Group &amp; Rombongan</option>
            </select>
          </div>
        </div>

        <div class="form-grid-3" style="margin-top: 18px;">
          <div class="form-group">
            <label class="form-label">Nominal Tarif (Angka) <span class="required">*</span></label>
            <input type="number" name="harga" class="form-input" placeholder="Contoh: 150000" min="0" step="5000" required>
            <span class="form-hint">Format angka tanpa titik atau koma</span>
          </div>

          <div class="form-group">
            <label class="form-label">Teks Format Tarif</label>
            <input type="text" name="harga_format" class="form-input" placeholder="Contoh: Rp 150.000 / Sesi">
            <span class="form-hint">Kosongkan jika ingin format otomatis</span>
          </div>

          <div class="form-group">
            <label class="form-label">Durasi Sesi <span class="required">*</span></label>
            <input type="text" name="durasi" class="form-input" placeholder="Contoh: 30 Menit Sesi" required>
          </div>
        </div>

        <div class="form-grid" style="margin-top: 18px;">
          <div class="form-group">
            <label class="form-label">Kapasitas Peserta <span class="required">*</span></label>
            <input type="text" name="kapasitas" class="form-input" placeholder="Contoh: Maksimal 4 Orang" required>
          </div>

          <div class="form-group">
            <label class="form-label">Label Sorotan / Tag Khusus</label>
            <input type="text" name="tag" class="form-input" placeholder="Contoh: Promo Spesial, Terfavorit, Baru">
            <span class="form-hint">Label penanda khusus pada kartu paket</span>
          </div>
        </div>

        <div class="form-grid" style="margin-top: 18px;">
          <div class="form-group">
            <label class="form-label">Pilih Foto Studio dari Aset</label>
            <select name="gambar_pilihan" id="select-gambar-pilihan" class="form-select">
              <?php foreach ($assetImages as $imgPath): ?>
                <option value="<?php echo htmlspecialchars($imgPath); ?>"><?php echo basename($imgPath); ?></option>
              <?php endforeach; ?>
            </select>
            <span class="form-hint">Pilih dari koleksi foto yang sudah ada</span>
          </div>

          <div class="form-group">
            <label class="form-label">Atau Unggah Foto Baru</label>
            <input type="file" name="gambar_upload" id="input-gambar-upload" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <span class="form-hint">Pilih file foto dari komputer atau HP</span>
          </div>
        </div>

        <!-- Tool Crop Gambar -->
        <div id="crop-box-wrapper" class="form-group full-width" style="display: none; margin-top: 18px; padding: 18px; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 6px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
            <div>
              <strong style="color: #112250; font-size: 0.92rem; display: block;">Alat Penyesuaian &amp; Pemangkasan Foto</strong>
              <span style="font-size: 0.78rem; color: #64748B;">Geser foto dengan mouse/touch dan gunakan zoom slider agar subjek pas di dalam bingkai wadah.</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <span style="font-size: 0.8rem; font-weight: 600; color: #475569;">Bentuk Wadah:</span>
              <select id="crop-ratio-select" style="padding: 4px 10px; border-radius: 4px; border: 1px solid #CBD5E1; font-size: 0.82rem; background: white; font-weight: 500;">
                <option value="4/3">4:3 (Standar Studio Potret - Rekomendasi)</option>
                <option value="1/1">1:1 (Persegi Simetris)</option>
                <option value="16/10">16:10 (Format Layar Lebar)</option>
              </select>
            </div>
          </div>

          <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 280px; max-width: 480px;">
              <div id="crop-viewport" style="position: relative; width: 100%; aspect-ratio: 4/3; background: #0A1533; border-radius: 4px; overflow: hidden; cursor: grab; user-select: none; border: 2px solid #112250; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                <img id="crop-source-img" src="" alt="Pratinjau Foto" style="position: absolute; top: 0; left: 0; transform-origin: top left; pointer-events: none; max-width: none; will-change: transform;">
                
                <div style="position: absolute; inset: 0; pointer-events: none; border: 1px dashed rgba(255,255,255,0.4); display: grid; grid-template-columns: 1fr 1fr 1fr; grid-template-rows: 1fr 1fr 1fr;">
                  <div style="border-right: 1px dashed rgba(255,255,255,0.25); border-bottom: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-right: 1px dashed rgba(255,255,255,0.25); border-bottom: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-bottom: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-right: 1px dashed rgba(255,255,255,0.25); border-bottom: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-right: 1px dashed rgba(255,255,255,0.25); border-bottom: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-bottom: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-right: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div style="border-right: 1px dashed rgba(255,255,255,0.25);"></div>
                  <div></div>
                </div>
              </div>

              
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 12px; background: white; padding: 8px 12px; border-radius: 4px; border: 1px solid #E2E8F0;">
                <span style="font-size: 0.8rem; font-weight: 600; color: #475569;">Zoom:</span>
                <input type="range" id="crop-zoom-range" min="100" max="300" value="100" style="flex: 1; accent-color: #112250; cursor: pointer;">
                <button type="button" id="btn-crop-center" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: 4px 12px; border-radius: 4px; font-size: 0.78rem; font-weight: 600; cursor: pointer;">Pusatkan Foto</button>
              </div>
            </div>

            
            <div style="min-width: 160px; max-width: 190px;">
              <span style="font-size: 0.78rem; font-weight: 600; color: #64748B; display: block; margin-bottom: 6px;">Pratinjau di Katalog:</span>
              <div id="crop-preview-box" style="width: 160px; aspect-ratio: 4/3; border-radius: 4px; overflow: hidden; border: 1px solid #CBD5E1; background: white; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                <canvas id="crop-canvas-preview" style="width: 100%; height: 100%; display: block;"></canvas>
              </div>
              <span style="font-size: 0.72rem; color: #64748B; display: block; margin-top: 6px; line-height: 1.4;">Foto akan disimpan otomatis dengan potongan ini.</span>
            </div>
          </div>

          <input type="hidden" name="gambar_cropped" id="gambar_cropped">
        </div>

        <div class="form-group full-width" style="margin-top: 18px;">
          <label class="form-label">Deskripsi Ringkas Layanan <span class="required">*</span></label>
          <textarea name="deskripsi" class="form-textarea" placeholder="Jelaskan ringkasan layanan pemotretan ini secara jelas dan lugas..." required></textarea>
        </div>

        <div class="form-group full-width" style="margin-top: 18px;">
          <label class="form-label">Daftar Fasilitas &amp; Keuntungan <span class="required">*</span></label>
          <textarea name="fasilitas" class="form-textarea" style="min-height: 120px;" placeholder="Tuliskan satu rincian fasilitas per baris. Contoh:&#10;30 Menit sesi foto bebas jepret&#10;FREE Semua File Original via Google Drive&#10;FREE Edit Color Grading 2 Foto Pilihan&#10;FREE Cetakan 10R 1 Lembar" required></textarea>
          <span class="form-hint">Setiap baris teks akan otomatis ditampilkan sebagai satu poin fasilitas.</span>
        </div>

        <div class="form-group full-width" style="margin-top: 16px;">
          <label class="checkbox-label">
            <input type="checkbox" name="rekomendasi" value="1">
            <span>Tandai paket ini sebagai Paket Rekomendasi (Muncul dengan tanda sorotan)</span>
          </label>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
          <button type="button" id="btn-batal-form" style="background: none; border: none; color: #64748B; font-size: 0.9rem; font-weight: 600; cursor: pointer; padding: 10px 18px;">Batal</button>
          <button type="submit" class="btn-submit-action">Simpan Paket Layanan</button>
        </div>
      </form>
    </section>

    <!-- Tabel Paket -->
    <section class="panel-card">
      <div class="panel-header">
        <h2 class="panel-title">Daftar Paket Terpasang</h2>
        <span class="panel-meta">Total: <?php echo $totalPaket; ?> Paket Layanan</span>
      </div>

      <div class="table-responsive">
        <table class="modern-table">
          <thead>
            <tr>
              <th style="width: 50px; text-align: center;">No</th>
              <th style="width: 80px;">Pratinjau</th>
              <th>Nama Paket Layanan</th>
              <th>Kategori</th>
              <th>Biaya &amp; Durasi</th>
              <th>Kapasitas</th>
              <th>Status</th>
              <th style="width: 140px; text-align: center;">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($queryPaket)): 
            ?>
            <tr>
              <td style="text-align: center; color: #64748B; font-weight: 600;"><?php echo $no++; ?></td>
              <td>
                <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Foto" class="thumb-preview">
              </td>
              <td>
                <div style="font-weight: 600; color: #112250;"><?php echo htmlspecialchars($row['nama']); ?></div>
                <?php if (!empty($row['tag'])): ?>
                  <span style="font-size: 0.72rem; color: #64748B; background: #F1F5F9; padding: 2px 6px; border-radius: 3px; display: inline-block; margin-top: 4px;"><?php echo htmlspecialchars($row['tag']); ?></span>
                <?php endif; ?>
              </td>
              <td>
                <span class="category-badge"><?php echo htmlspecialchars($row['kategori_label']); ?></span>
              </td>
              <td>
                <div style="font-weight: 600;"><?php echo htmlspecialchars($row['harga_format']); ?></div>
                <div style="font-size: 0.78rem; color: #64748B; margin-top: 2px;"><?php echo htmlspecialchars($row['durasi']); ?></div>
              </td>
              <td style="font-size: 0.82rem; color: #475569;">
                <?php echo htmlspecialchars($row['kapasitas']); ?>
              </td>
              <td>
                <?php if ($row['rekomendasi']): ?>
                  <span class="badge-rekomendasi">Rekomendasi</span>
                <?php else: ?>
                  <span style="font-size: 0.76rem; color: #94A3B8;">Standar</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <div class="action-links" style="justify-content: center;">
                  <a href="edit_paket.php?admin=smart22studio&id=<?php echo $row['id']; ?>" class="btn-edit-link">Edit</a>
                  <a href="hapus_paket.php?admin=smart22studio&id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus paket layanan ini dari katalog?')" class="btn-delete-link">Hapus</a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>

            <?php if ($totalPaket === 0): ?>
            <tr>
              <td colspan="8" style="text-align: center; padding: 40px; color: #64748B;">
                Belum ada paket layanan yang terdaftar. Silakan tambahkan melalui formulir di atas.
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <script src="js/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      const $formPanel = $("#form-tambah-panel");
      const $btnToggle = $("#btn-toggle-tambah");

      $btnToggle.on("click", function() {
        if ($formPanel.is(":visible")) {
          $formPanel.slideUp(200);
          $btnToggle.text("+ Tambah Paket Baru");
        } else {
          $formPanel.slideDown(250);
          $btnToggle.text("Tutup Formulir ×");
          $('html, body').animate({
            scrollTop: $formPanel.offset().top - 20
          }, 300);
        }
      });

      $("#btn-tutup-form, #btn-batal-form").on("click", function() {
        $formPanel.slideUp(200);
        $btnToggle.text("+ Tambah Paket Baru");
      });

      // Crop foto
      const $cropBox = $("#crop-box-wrapper");
      const $cropViewport = $("#crop-viewport");
      const $cropImg = $("#crop-source-img");
      const $zoomRange = $("#crop-zoom-range");
      const $btnCenter = $("#btn-crop-center");
      const $ratioSelect = $("#crop-ratio-select");
      const $inputUpload = $("#input-gambar-upload");
      const $selectAsset = $("#select-gambar-pilihan");
      const previewCanvas = document.getElementById("crop-canvas-preview");
      const previewCtx = previewCanvas ? previewCanvas.getContext("2d") : null;

      let sourceImage = new Image();
      let imageLoaded = false;
      let posX = 0, posY = 0;
      let baseScale = 1;
      let currentScale = 1;
      let isDragging = false;
      let startMouseX = 0, startMouseY = 0;
      let startPosX = 0, startPosY = 0;

      function updateRatio(ratioStr) {
        $cropViewport.css("aspect-ratio", ratioStr);
        $("#crop-preview-box").css("aspect-ratio", ratioStr);
        if (imageLoaded) {
          centerAndResetImage();
        }
      }

      $ratioSelect.on("change", function() {
        updateRatio($(this).val());
      });

      function clampPositions() {
        const vpW = $cropViewport.width();
        const vpH = $cropViewport.height();
        const scaledW = sourceImage.naturalWidth * currentScale;
        const scaledH = sourceImage.naturalHeight * currentScale;

        const minX = vpW - scaledW;
        const minY = vpH - scaledH;

        posX = Math.min(0, Math.max(minX, posX));
        posY = Math.min(0, Math.max(minY, posY));
      }

      function renderViewportAndPreview() {
        if (!imageLoaded) return;
        clampPositions();

        $cropImg.css({
          transform: "translate(" + posX + "px, " + posY + "px) scale(" + currentScale + ")"
        });

        if (previewCanvas && previewCtx) {
          const vpW = $cropViewport.width();
          const vpH = $cropViewport.height();
          previewCanvas.width = 320;
          previewCanvas.height = Math.round(320 * (vpH / vpW));

          const scaleRatio = previewCanvas.width / vpW;
          previewCtx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
          previewCtx.drawImage(
            sourceImage,
            posX * scaleRatio,
            posY * scaleRatio,
            sourceImage.naturalWidth * currentScale * scaleRatio,
            sourceImage.naturalHeight * currentScale * scaleRatio
          );
        }
      }

      function centerAndResetImage() {
        if (!imageLoaded) return;
        const vpW = $cropViewport.width();
        const vpH = $cropViewport.height();


        baseScale = Math.max(vpW / sourceImage.naturalWidth, vpH / sourceImage.naturalHeight);
        currentScale = baseScale;
        $zoomRange.val(100);

        const scaledW = sourceImage.naturalWidth * currentScale;
        const scaledH = sourceImage.naturalHeight * currentScale;
        posX = (vpW - scaledW) / 2;
        posY = (vpH - scaledH) / 2;

        renderViewportAndPreview();
      }

      function loadPhotoIntoCropper(srcUrl) {
        sourceImage = new Image();
        sourceImage.crossOrigin = "anonymous";
        sourceImage.onload = function() {
          imageLoaded = true;
          $cropImg.attr("src", srcUrl);
          $cropBox.slideDown(200, function() {
            centerAndResetImage();
          });
        };
        sourceImage.src = srcUrl;
      }


      $inputUpload.on("change", function(e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(evt) {
            loadPhotoIntoCropper(evt.target.result);
          };
          reader.readAsDataURL(file);
        }
      });


      $selectAsset.on("change", function() {
        const assetPath = $(this).val();
        if (assetPath) {
          loadPhotoIntoCropper(assetPath);
        }
      });


      if ($selectAsset.val()) {
        loadPhotoIntoCropper($selectAsset.val());
      }


      $zoomRange.on("input", function() {
        if (!imageLoaded) return;
        const zoomFactor = parseFloat($(this).val()) / 100;
        const oldScale = currentScale;
        currentScale = baseScale * zoomFactor;

        const vpW = $cropViewport.width();
        const vpH = $cropViewport.height();
        const cx = vpW / 2;
        const cy = vpH / 2;

        const imgX = (cx - posX) / oldScale;
        const imgY = (cy - posY) / oldScale;

        posX = cx - imgX * currentScale;
        posY = cy - imgY * currentScale;

        renderViewportAndPreview();
      });


      $btnCenter.on("click", function() {
        centerAndResetImage();
      });


      $cropViewport.on("mousedown touchstart", function(e) {
        if (!imageLoaded) return;
        isDragging = true;
        $cropViewport.css("cursor", "grabbing");

        const pageX = e.pageX || (e.originalEvent.touches && e.originalEvent.touches[0].pageX);
        const pageY = e.pageY || (e.originalEvent.touches && e.originalEvent.touches[0].pageY);

        startMouseX = pageX;
        startMouseY = pageY;
        startPosX = posX;
        startPosY = posY;
        e.preventDefault();
      });

      $(window).on("mousemove touchmove", function(e) {
        if (!isDragging || !imageLoaded) return;

        const pageX = e.pageX || (e.originalEvent.touches && e.originalEvent.touches[0].pageX);
        const pageY = e.pageY || (e.originalEvent.touches && e.originalEvent.touches[0].pageY);

        const dx = pageX - startMouseX;
        const dy = pageY - startMouseY;

        posX = startPosX + dx;
        posY = startPosY + dy;

        renderViewportAndPreview();
      });

      $(window).on("mouseup touchend", function() {
        if (isDragging) {
          isDragging = false;
          $cropViewport.css("cursor", "grab");
        }
      });


      $("#form-tambah-panel form").on("submit", function() {
        if (imageLoaded) {
          const vpW = $cropViewport.width();
          const vpH = $cropViewport.height();

          const exportCanvas = document.createElement("canvas");
          exportCanvas.width = 1200;
          exportCanvas.height = Math.round(1200 * (vpH / vpW));

          const exportCtx = exportCanvas.getContext("2d");
          const factor = exportCanvas.width / vpW;

          exportCtx.drawImage(
            sourceImage,
            posX * factor,
            posY * factor,
            sourceImage.naturalWidth * currentScale * factor,
            sourceImage.naturalHeight * currentScale * factor
          );

          const dataJpeg = exportCanvas.toDataURL("image/jpeg", 0.9);
          $("#gambar_cropped").val(dataJpeg);
        }
      });
    });
  </script>
</body>
</html>
