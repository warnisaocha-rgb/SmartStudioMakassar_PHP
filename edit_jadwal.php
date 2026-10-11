<?php
include "config/db.php";

// Cek akses admin
if (!isset($_GET['admin']) || $_GET['admin'] != 'smart22studio') {
    die("Akses ditolak! Fitur ini hanya untuk Admin.");
}

// Ambil data jadwal berdasarkan ID
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM jadwal WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Jadwal | Smart Studio</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/responsive.css">
</head>
<body style="background: #F8F9FA; padding: 50px 20px; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;">

  <div style="max-width: 560px; margin: 0 auto; background: white; padding: 32px; border-radius: 8px; border: 1px solid #E2E8F0; box-shadow: 0 4px 14px rgba(17, 34, 80, 0.04);">
      <div style="border-bottom: 1px solid #F1F5F9; padding-bottom: 14px; margin-bottom: 20px; text-align: center;">
          <h2 style="color: #112250; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.8rem; margin: 0;">Edit Jadwal Pemotretan</h2>
          <span style="font-size: 0.85rem; color: #64748B;">Perbarui informasi jadwal pelanggan</span>
      </div>
      
      <form action="update_jadwal.php?admin=smart22studio" method="POST">
          <input type="hidden" name="admin" value="smart22studio">
          <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
          
          <div style="margin-bottom: 16px;">
              <label style="font-size: 0.85rem; font-weight: 600; color: #1E293B; display: block; margin-bottom: 6px;">Nama Pelanggan:</label>
              <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid #CBD5E1; font-size: 0.9rem; box-sizing: border-box;">
          </div>

          <div style="margin-bottom: 16px;">
              <label style="font-size: 0.85rem; font-weight: 600; color: #1E293B; display: block; margin-bottom: 6px;">Pilihan Paket:</label>
              <select name="paket" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid #CBD5E1; font-size: 0.9rem; box-sizing: border-box;">
                  <?php 
                  $paketOptions = mysqli_query($conn, "SELECT nama FROM paket ORDER BY id ASC");
                  $selectedFound = false;
                  if ($paketOptions && mysqli_num_rows($paketOptions) > 0) {
                      while ($p = mysqli_fetch_assoc($paketOptions)) {
                          $isSelected = ($data['paket'] === $p['nama']) ? 'selected' : '';
                          if ($isSelected) $selectedFound = true;
                          echo '<option value="' . htmlspecialchars($p['nama']) . '" ' . $isSelected . '>' . htmlspecialchars($p['nama']) . '</option>';
                      }
                  }
                  if (!$selectedFound && !empty($data['paket'])) {
                      echo '<option value="' . htmlspecialchars($data['paket']) . '" selected>' . htmlspecialchars($data['paket']) . '</option>';
                  }
                  ?>
              </select>
          </div>

          <div style="margin-bottom: 16px;">
              <label style="font-size: 0.85rem; font-weight: 600; color: #1E293B; display: block; margin-bottom: 6px;">Tanggal Sesi:</label>
              <input type="date" name="tanggal" value="<?php echo $data['tanggal']; ?>" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid #CBD5E1; font-size: 0.9rem; box-sizing: border-box;">
          </div>
          
          <div style="margin-bottom: 24px;">
              <label style="font-size: 0.85rem; font-weight: 600; color: #1E293B; display: block; margin-bottom: 6px;">Waktu / Jam Sesi:</label>
              <input type="text" name="jam" value="<?php echo htmlspecialchars($data['jam']); ?>" placeholder="Misal: 10:00 - 12:00" required style="width: 100%; padding: 10px 14px; border-radius: 6px; border: 1px solid #CBD5E1; font-size: 0.9rem; box-sizing: border-box;">
          </div>

          <button type="submit" name="update" style="width: 100%; padding: 12px; background: #112250; color: #FFFFFF; font-weight: 600; font-size: 0.92rem; border: 1px solid #112250; border-radius: 6px; cursor: pointer;">Simpan Perubahan</button>
          
      </form>
  </div>

</body>
</html>