<?php
require_once __DIR__ . '/config/db.php';

// Cek admin
if (!isset($_GET['admin']) || $_GET['admin'] !== 'smart22studio') {
    die("Akses ditolak! Halaman ini dikhususkan untuk Administrator Smart Studio.");
}

$id = (int)($_GET['id'] ?? 0);
$query = mysqli_query($conn, "SELECT * FROM paket WHERE id = $id");
if (!$query || mysqli_num_rows($query) === 0) {
    die("Data paket tidak ditemukan.");
}

$paket = mysqli_fetch_assoc($query);

// Format fasilitas
$fasilitasText = '';
if (!empty($paket['fasilitas'])) {
    $decoded = json_decode($paket['fasilitas'], true);
    if (is_array($decoded)) {
        $fasilitasText = implode("\n", $decoded);
    } else {
        $fasilitasText = $paket['fasilitas'];
    }
}

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
  <title>Perbarui Data Paket | Smart Studio Makassar</title>
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
      max-width: 900px;
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

    .panel-card {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-radius: 8px;
      padding: 32px;
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
      font-size: 1.25rem;
      font-weight: 700;
      color: #112250;
      margin: 0;
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
      min-height: 100px;
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
    }

    .checkbox-label input[type="checkbox"] {
      width: 17px;
      height: 17px;
      accent-color: #112250;
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
      transition: background 0.2s ease;
    }

    .btn-submit-action:hover {
      background: #0A1533;
    }

    .btn-cancel-link {
      display: inline-flex;
      align-items: center;
      padding: 12px 22px;
      font-size: 0.92rem;
      font-weight: 600;
      color: #64748B;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .btn-cancel-link:hover {
      color: #0F172A;
    }

    .current-image-preview {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 12px;
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 6px;
      margin-bottom: 10px;
    }

    .current-image-preview img {
      width: 72px;
      height: 54px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid #CBD5E1;
    }

    @media (max-width: 768px) {
      .form-grid, .form-grid-3 {
        grid-template-columns: 1fr;
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
      <div style="display: flex; align-items: center; gap: 12px;">
        <span class="admin-badge-indicator">Administrator</span>
        <span class="admin-brand-title">Perbarui Data Layanan</span>
      </div>
      <a href="kelola_paket.php?admin=smart22studio" class="btn-cancel-link">&larr; Kembali ke Daftar Paket</a>
    </div>

    <section class="panel-card">
      <div class="panel-header">
        <h1 class="panel-title">Edit Paket: <?php echo htmlspecialchars($paket['nama']); ?></h1>
        <span style="font-size: 0.85rem; color: #64748B;">ID: #<?php echo $paket['id']; ?></span>
      </div>

      <form action="update_paket.php?admin=smart22studio" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="admin" value="smart22studio">
        <input type="hidden" name="id" value="<?php echo $paket['id']; ?>">
        <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($paket['gambar']); ?>">

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Paket Layanan <span class="required">*</span></label>
            <input type="text" name="nama" class="form-input" value="<?php echo htmlspecialchars($paket['nama']); ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Kategori Layanan <span class="required">*</span></label>
            <select name="kategori" class="form-select" required>
              <option value="self-photo" <?php echo ($paket['kategori'] === 'self-photo') ? 'selected' : ''; ?>>Self Photo</option>
              <option value="wisuda" <?php echo ($paket['kategori'] === 'wisuda') ? 'selected' : ''; ?>>Wisuda</option>
              <option value="formal" <?php echo ($paket['kategori'] === 'formal') ? 'selected' : ''; ?>>Pas Foto, Nikah &amp; Ijazah</option>
              <option value="keluarga" <?php echo ($paket['kategori'] === 'keluarga') ? 'selected' : ''; ?>>Foto Keluarga</option>
              <option value="group" <?php echo ($paket['kategori'] === 'group') ? 'selected' : ''; ?>>Group &amp; Rombongan</option>
            </select>
          </div>
        </div>

        <div class="form-grid-3" style="margin-top: 18px;">
          <div class="form-group">
            <label class="form-label">Nominal Tarif (Angka) <span class="required">*</span></label>
            <input type="number" name="harga" class="form-input" value="<?php echo (int)$paket['harga']; ?>" min="0" step="5000" required>
          </div>

          <div class="form-group">
            <label class="form-label">Teks Format Tarif</label>
            <input type="text" name="harga_format" class="form-input" value="<?php echo htmlspecialchars($paket['harga_format']); ?>">
          </div>

          <div class="form-group">
            <label class="form-label">Durasi Sesi <span class="required">*</span></label>
            <input type="text" name="durasi" class="form-input" value="<?php echo htmlspecialchars($paket['durasi']); ?>" required>
          </div>
        </div>

        <div class="form-grid" style="margin-top: 18px;">
          <div class="form-group">
            <label class="form-label">Kapasitas Peserta <span class="required">*</span></label>
            <input type="text" name="kapasitas" class="form-input" value="<?php echo htmlspecialchars($paket['kapasitas']); ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Label Sorotan / Tag Khusus</label>
            <input type="text" name="tag" class="form-input" value="<?php echo htmlspecialchars($paket['tag']); ?>">
          </div>
        </div>

        <div class="form-group full-width" style="margin-top: 18px;">
          <label class="form-label">Foto Saat Ini</label>
          <div class="current-image-preview">
            <img src="<?php echo htmlspecialchars($paket['gambar']); ?>" alt="Foto Sekarang">
            <div style="flex: 1;">
              <div style="font-size: 0.88rem; font-weight: 600;"><?php echo htmlspecialchars($paket['gambar']); ?></div>
              <span class="form-hint">Jika tidak ingin mengganti atau memangkas foto, biarkan pilihan di bawah ini kosong.</span>
            </div>
            <button type="button" id="btn-toggle-cropper" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #112250; padding: 7px 14px; border-radius: 4px; font-size: 0.82rem; font-weight: 600; cursor: pointer;">
              Pangkas / Sesuaikan Foto Ini
            </button>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Ganti dari Foto Aset Studio</label>
            <select name="gambar_pilihan" id="select-gambar-pilihan" class="form-select">
              <option value="">-- Tetap Gunakan Foto Saat Ini --</option>
              <?php foreach ($assetImages as $imgPath): ?>
                <option value="<?php echo htmlspecialchars($imgPath); ?>" <?php echo ($paket['gambar'] === $imgPath) ? 'selected' : ''; ?>><?php echo basename($imgPath); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Atau Unggah Foto Baru</label>
            <input type="file" name="gambar_upload" id="input-gambar-upload" class="form-input" accept=".jpg,.jpeg,.png,.webp">
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
          <textarea name="deskripsi" class="form-textarea" required><?php echo htmlspecialchars($paket['deskripsi']); ?></textarea>
        </div>

        <div class="form-group full-width" style="margin-top: 18px;">
          <label class="form-label">Daftar Fasilitas &amp; Keuntungan <span class="required">*</span></label>
          <textarea name="fasilitas" class="form-textarea" style="min-height: 140px;" required><?php echo htmlspecialchars($fasilitasText); ?></textarea>
          <span class="form-hint">Setiap baris teks akan otomatis diproses sebagai satu poin fasilitas.</span>
        </div>

        <div class="form-group full-width" style="margin-top: 16px;">
          <label class="checkbox-label">
            <input type="checkbox" name="rekomendasi" value="1" <?php echo ($paket['rekomendasi'] == 1) ? 'checked' : ''; ?>>
            <span>Tandai paket ini sebagai Paket Rekomendasi</span>
          </label>
        </div>

        <div style="margin-top: 28px; display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
          <a href="kelola_paket.php?admin=smart22studio" class="btn-cancel-link">Batal</a>
          <button type="submit" class="btn-submit-action">Simpan Perubahan Paket</button>
        </div>
      </form>
    </section>

  </main>

  <script src="js/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      // Crop foto
      const $cropBox = $("#crop-box-wrapper");
      const $cropViewport = $("#crop-viewport");
      const $cropImg = $("#crop-source-img");
      const $zoomRange = $("#crop-zoom-range");
      const $btnCenter = $("#btn-crop-center");
      const $ratioSelect = $("#crop-ratio-select");
      const $inputUpload = $("#input-gambar-upload");
      const $selectAsset = $("#select-gambar-pilihan");
      const $btnToggleCropper = $("#btn-toggle-cropper");
      const previewCanvas = document.getElementById("crop-canvas-preview");
      const previewCtx = previewCanvas ? previewCanvas.getContext("2d") : null;

      let sourceImage = new Image();
      let imageLoaded = false;
      let isCropperActive = false;
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
        if (!srcUrl) return;
        sourceImage = new Image();
        sourceImage.crossOrigin = "anonymous";
        sourceImage.onload = function() {
          imageLoaded = true;
          isCropperActive = true;
          $cropImg.attr("src", srcUrl);
          $cropBox.slideDown(200, function() {
            centerAndResetImage();
          });
        };
        sourceImage.src = srcUrl;
      }


      $btnToggleCropper.on("click", function() {
        loadPhotoIntoCropper("<?php echo htmlspecialchars($paket['gambar']); ?>");
        $('html, body').animate({
          scrollTop: $cropBox.offset().top - 40
        }, 300);
      });


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


      $("form").on("submit", function() {
        if (isCropperActive && imageLoaded) {
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
