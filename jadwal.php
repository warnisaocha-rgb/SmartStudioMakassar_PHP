<?php 
require_once __DIR__ . '/config/db.php';

// Cek admin
$is_admin = false;
if (isset($_GET['admin']) && $_GET['admin'] === 'smart22studio') {
    $is_admin = true;
}

// Ambil data jadwal
$query = mysqli_query($conn, "SELECT * FROM jadwal ORDER BY tanggal ASC, jam ASC");
$totalJadwal = $query ? mysqli_num_rows($query) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informasi Jadwal Booking | Smart Studio Makassar</title>
  <link rel="icon" type="image/png" href="assets/logo.png">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
  <style>
    body {
      background-color: #FAF8F5;
      color: #112250;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .admin-container {
      max-width: 1100px;
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
      margin: 0 0 8px 0;
      letter-spacing: -0.5px;
    }

    .page-subheading {
      margin: 0;
      font-size: 0.95rem;
      color: #64748B;
      line-height: 1.6;
    }


    .panel-card {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-radius: 8px;
      padding: 28px;
      margin-bottom: 28px;
      box-shadow: 0 4px 14px rgba(17, 34, 80, 0.04);
    }

    .panel-header {
      border-bottom: 1px solid #F1F5F9;
      padding-bottom: 16px;
      margin-bottom: 22px;
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

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
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

    .form-input, .form-select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #CBD5E1;
      border-radius: 6px;
      font-size: 0.9rem;
      font-family: inherit;
      background: #FFFFFF;
      color: #0F172A;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-input:focus, .form-select:focus {
      outline: none;
      border-color: #112250;
      box-shadow: 0 0 0 3px rgba(17, 34, 80, 0.08);
    }

    .form-hint {
      font-size: 0.78rem;
      color: #64748B;
      margin-top: 2px;
    }

    .btn-submit-action {
      background: #112250;
      color: #FFFFFF;
      border: 1px solid #112250;
      border-radius: 6px;
      padding: 11px 26px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .btn-submit-action:hover {
      background: #0A1533;
    }


    .table-responsive {
      overflow-x: auto;
      border-radius: 8px;
      border: 1px solid #E2E8F0;
      background: #FFFFFF;
      box-shadow: 0 4px 14px rgba(17, 34, 80, 0.04);
    }

    .modern-table {
      width: 100%;
      border-collapse: collapse;
      background: #FFFFFF;
      font-size: 0.88rem;
    }

    .modern-table th {
      background: #112250;
      color: #FFFFFF;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.76rem;
      letter-spacing: 0.6px;
      padding: 14px 18px;
      text-align: left;
      border-bottom: 1px solid #112250;
    }

    .modern-table td {
      padding: 15px 18px;
      border-bottom: 1px solid #F1F5F9;
      color: #1E293B;
      vertical-align: middle;
    }

    .modern-table tr:last-child td {
      border-bottom: none;
    }

    .modern-table tr:hover td {
      background: #FBFBFB;
    }

    .package-badge {
      display: inline-block;
      font-size: 0.78rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 4px;
      background: #EEF2F7;
      color: #112250;
    }

    .date-badge {
      font-weight: 600;
      color: #1E293B;
    }

    .time-badge {
      font-size: 0.82rem;
      color: #475569;
    }

    .action-links {
      display: flex;
      gap: 6px;
      align-items: center;
      justify-content: center;
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
      .form-grid {
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

    
    <?php if (!$is_admin): ?>
    <div style="margin-bottom: 24px;">
      <a href="paket.html" style="display: inline-flex; align-items: center; gap: 8px; color: #112250; text-decoration: none; font-weight: 600; font-size: 0.9rem; padding: 8px 18px; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 6px; transition: background 0.15s ease;">
        &larr; Kembali ke Katalog Paket
      </a>
    </div>
    <?php endif; ?>

    
    <?php if ($is_admin): ?>
    <div class="admin-header-nav">
      <div class="admin-brand-info">
        <span class="admin-badge-indicator">Administrator</span>
        <span class="admin-brand-title">Panel Manajemen Studio</span>
      </div>
      <nav class="admin-nav-links">
        <a href="jadwal.php?admin=smart22studio" class="admin-nav-btn active">Jadwal Pemotretan</a>
        <a href="kelola_paket.php?admin=smart22studio" class="admin-nav-btn">Katalog Paket Foto</a>
        <a href="jadwal.php" target="_blank" class="admin-nav-btn preview-btn">Lihat Halaman Pengguna &rarr;</a>
      </nav>
    </div>
    <?php endif; ?>

    
    <div class="page-title-box" style="<?php echo $is_admin ? 'display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;' : 'text-align: center;'; ?>">
      <div>
        <h1 class="page-heading">Status Jadwal Booking</h1>
      </div>

      <?php if ($is_admin): ?>
      <div>
        <button type="button" id="btn-toggle-tambah" class="btn-submit-action">
          + Tambah Jadwal Baru
        </button>
      </div>
      <?php endif; ?>
    </div>

    <!-- Form Tambah Jadwal -->
    <?php if ($is_admin): ?>
    <section class="panel-card" id="form-tambah-panel" style="display: none; margin-bottom: 28px;">
      <div class="panel-header">
        <div>
          <h2 class="panel-title">Tambah Jadwal Pemotretan Baru</h2>
        </div>
        <button type="button" id="btn-tutup-form" style="background: none; border: none; color: #64748B; font-size: 0.85rem; font-weight: 600; cursor: pointer; padding: 4px 8px;">Tutup Formulir &times;</button>
      </div>

      <form action="save_jadwal.php?admin=smart22studio" method="POST">
        <input type="hidden" name="admin" value="smart22studio">

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nama Pelanggan <span class="required">*</span></label>
            <input type="text" name="nama" class="form-input" placeholder="Masukkan nama lengkap pelanggan" required>
          </div>

          <div class="form-group">
            <label class="form-label">Pilihan Paket Layanan <span class="required">*</span></label>
            <select name="paket" class="form-select" required>
              <?php 
              $paketOptions = mysqli_query($conn, "SELECT nama FROM paket ORDER BY id ASC");
              if ($paketOptions && mysqli_num_rows($paketOptions) > 0) {
                  while ($p = mysqli_fetch_assoc($paketOptions)) {
                      echo '<option value="' . htmlspecialchars($p['nama']) . '">' . htmlspecialchars($p['nama']) . '</option>';
                  }
              } else {
                  echo '<option value="Self Photo Remote">Self Photo Remote</option>';
                  echo '<option value="Foto Wisuda (Promo Graduation)">Foto Wisuda (Promo Graduation)</option>';
                  echo '<option value="Pas Foto Buku Nikah (KUA)">Pas Foto Buku Nikah (KUA)</option>';
                  echo '<option value="Foto Personal Formal &amp; Non-Formal">Foto Personal Formal &amp; Non-Formal</option>';
                  echo '<option value="Foto Keluarga (All In)">Foto Keluarga (All In)</option>';
                  echo '<option value="Foto Group / Rombongan">Foto Group / Rombongan</option>';
                  echo '<option value="Pas Foto Formal">Pas Foto Formal</option>';
                  echo '<option value="Foto Ijazah Jas / Almamater">Foto Ijazah Jas / Almamater</option>';
                  echo '<option value="Foto Ijazah Toga">Foto Ijazah Toga</option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Tanggal Sesi Pemotretan <span class="required">*</span></label>
            <input type="date" name="tanggal" class="form-input" required>
          </div>

          <div class="form-group">
            <label class="form-label">Waktu / Jam Sesi <span class="required">*</span></label>
            <input type="text" name="jam" class="form-input" placeholder="Contoh: 10:00 - 12:00 WITA" required>
            <span class="form-hint">Tuliskan estimasi jam pemotretan pelanggan</span>
          </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
          <button type="button" id="btn-batal-form" style="background: none; border: none; color: #64748B; font-size: 0.9rem; font-weight: 600; cursor: pointer; padding: 10px 16px;">Batal</button>
          <button type="submit" name="tambah" class="btn-submit-action">Simpan Jadwal Pemotretan</button>
        </div>
      </form>
    </section>
    <?php endif; ?>

    <!-- Tabel Jadwal -->
    <div class="table-responsive">
      <table class="modern-table">
        <thead>
          <tr>
            <th style="width: 50px; text-align: center;">No</th>
            <th>Nama Pelanggan</th>
            <th>Paket Layanan</th>
            <th>Tanggal Sesi</th>
            <th>Jam Sesi</th>
            <?php if ($is_admin): ?>
            <th style="width: 140px; text-align: center;">Aksi Admin</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php 
          if ($totalJadwal > 0):
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)):
                $tglFormatted = date('d F Y', strtotime($row['tanggal']));
                $months = [
                  'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                  'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                  'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                  'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                ];
                $tglFormatted = strtr($tglFormatted, $months);
          ?>
          <tr>
            <td style="text-align: center; color: #64748B; font-weight: 600;"><?php echo $no++; ?></td>
            <td>
              <strong style="color: #112250;"><?php echo htmlspecialchars($row['nama']); ?></strong>
            </td>
            <td>
              <span class="package-badge"><?php echo htmlspecialchars($row['paket']); ?></span>
            </td>
            <td>
              <span class="date-badge"><?php echo htmlspecialchars($tglFormatted); ?></span>
            </td>
            <td>
              <span class="time-badge"><?php echo htmlspecialchars($row['jam'] ?? '-'); ?></span>
            </td>
            <?php if ($is_admin): ?>
            <td style="text-align: center;">
              <div class="action-links">
                <a href="edit_jadwal.php?admin=smart22studio&id=<?php echo $row['id']; ?>" class="btn-edit-link">Edit</a>
                <a href="hapus_jadwal.php?admin=smart22studio&id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')" class="btn-delete-link">Hapus</a>
              </div>
            </td>
            <?php endif; ?>
          </tr>
          <?php endwhile; ?>
          <?php else: ?>
          <tr>
            <td colspan="<?php echo $is_admin ? '6' : '5'; ?>" style="text-align: center; padding: 48px 20px; color: #64748B;">
              <div style="font-size: 0.95rem; font-weight: 600; color: #112250; margin-bottom: 4px;">Belum Ada Jadwal Ter-booking</div>
              <div style="font-size: 0.85rem; color: #64748B;">Semua tanggal dan sesi pemotretan saat ini masih tersedia.</div>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>

  <script src="js/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      const $formPanel = $("#form-tambah-panel");
      const $btnToggle = $("#btn-toggle-tambah");

      if ($btnToggle.length) {
        $btnToggle.on("click", function() {
          if ($formPanel.is(":visible")) {
            $formPanel.slideUp(200);
            $btnToggle.text("+ Tambah Jadwal Baru");
          } else {
            $formPanel.slideDown(250);
            $btnToggle.text("Tutup Formulir \u00d7");
            $('html, body').animate({
              scrollTop: $formPanel.offset().top - 20
            }, 300);
          }
        });

        $("#btn-tutup-form, #btn-batal-form").on("click", function() {
          $formPanel.slideUp(200);
          $btnToggle.text("+ Tambah Jadwal Baru");
        });
      }
    });
  </script>
</body>
</html>