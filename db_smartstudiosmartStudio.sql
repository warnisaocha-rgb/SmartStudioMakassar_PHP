-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2026 at 01:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_smartstudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `paket` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `nama`, `paket`, `tanggal`, `jam`) VALUES
(5, 'Warnisyah Armin', 'Foto Keluarga (All In)', '2026-01-11', '10.00-10.30'),
(10, 'Nurliani', 'Self Photo Remote (Hanya 25K / Orang)', '2026-10-10', '10.30-11.00');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id` int(11) NOT NULL,
  `kode_paket` varchar(100) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `kategori_label` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_format` varchar(100) NOT NULL,
  `durasi` varchar(100) NOT NULL,
  `kapasitas` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `fasilitas` text NOT NULL,
  `tag` varchar(100) DEFAULT '',
  `rekomendasi` tinyint(1) DEFAULT 0,
  `gambar` varchar(255) DEFAULT 'assets/self-foto.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id`, `kode_paket`, `nama`, `kategori`, `kategori_label`, `harga`, `harga_format`, `durasi`, `kapasitas`, `deskripsi`, `fasilitas`, `tag`, `rekomendasi`, `gambar`, `created_at`) VALUES
(1, 'self-photo-remote-25k', 'Self Photo Remote (Hanya 25K / Orang)', 'self-photo', 'Self Photo', 25000, 'Rp 25.000 / Orang', '1 Sesi = 30 Menit Foto', 'Minimal 2 Orang — Maksimal 10 Orang', 'Pricelist baru self-photo mandiri dengan remote klik nirkabel. 1 sesi 30 menit puas, bebas berekspresi tanpa rasa canggung.', '[\"Hanya Rp 25.000 \\/ Orang (Minimal 2 orang \\/ Maksimal 10 orang)\",\"1 Sesi = Durasi Panjang 30 Menit Foto Bebas Jepret Sepuasnya\",\"File Only (Tanpa cetakan, opsi tambah cetak 4R: 5k & 10R: 15k)\",\"FREE ALL Files via Google Drive \\/ Flashdisk \\/ HP Android\",\"FREE Edit Color Grading (Optional \\/ Sesuai Permintaan)\",\"1 Sesi = 1 Pilihan Warna Background (Tambah Background = Tambah Sesi)\",\"Bebas Ganti Kostum (*Durasi waktu sesi tetap berjalan)\",\"Bebas Penggunaan Aksesoris Studio (Kacamata, Kursi, Bunga, dll)\",\"Privasi Terjamin: Bisa request jika foto tidak ingin diupload\\/publish\"]', 'Pricelist Baru 25K', 1, 'assets/paket-1791648697-582.jpg', '2026-10-07 02:10:46'),
(2, 'wisuda-promo', 'Paket Foto Wisuda (Promo Graduation)', 'wisuda', 'Wisuda', 200000, 'Rp 200.000', '± 30 Menit Sesi', 'Maksimal 5 Orang (Lebih dari 5 org +10k/org)', 'Paket foto wisuda lengkap bersama fotografer profesional. Abadikan momen kelulusan sendiri dan bersama keluarga (Maksimal 5 Orang, jika lebih +10rb/orang).', '[\"40 - 60 Jepretan Foto\",\"FREE Cetak Foto 10R = 2 Lembar\",\"FREE ALL FILE via Link Google Drive\",\"FREE Edit Color Preset \\/ Color Correction\",\"Foto Sendiri (Pakai & Lepas Toga)\",\"Foto Bertiga dengan Orang Tua\",\"Foto Berdua dengan Bapak, lalu Ibu\",\"Foto bersama Keluarga Inti\",\"Maksimal 5 Orang (+Rp 10.000\\/Orang tambahan)\",\"1 Background \\/ Latar & 1 Kostum\"]', 'Promo Wisuda Favorit', 1, 'assets/paket-1791648746-497.jpg', '2026-10-07 02:10:46'),
(3, 'pas-foto-nikah', 'Pas Foto Buku Nikah (KUA / Pasangan)', 'formal', 'Pas Foto, Nikah & Ijazah', 50000, 'Rp 50.000 / Orang', '± 15 Menit Sesi', 'Calon Pengantin (1 Orang / Sepasang)', 'Khusus pas foto resmi buku nikah KUA. Hasil simetris rapi sesuai standar resmi Kemenag RI.', '[\"FREE Cetakan 12 Lembar Kertas Foto Standar Buku Nikah\",\"JIKA BERSAMA PASANGAN: FREE Foto Gandeng \\/ Berdampingan 3-4 Kali Pose\",\"FREE Edit Simetris, Proporsional, & Bersih Mulus\",\"FREE Semua File Original dikirim via WA \\/ Google Drive\",\"Pilihan Ganti Background Biru atau Merah Standar KUA\"]', 'Buku Nikah KUA', 1, 'assets/foto-nikah-gandeng.jpg', '2026-10-07 02:10:46'),
(4, 'foto-personal-50k', 'Foto Personal — Formal & Non-Formal', 'formal', 'Pas Foto, Nikah & Ijazah', 50000, 'Rp 50.000', '± 15 Menit Sesi', '1 Orang', 'Paket foto personal untuk lamaran kerja, profil LinkedIn, administrasi kantor, atau kebutuhan branding diri.', '[\"3 - 5 Pose \\/ Gaya (Pas Foto Formal, Close Up, Semi Formal, Full Body)\",\"FREE ALL Ori Files via Google Drive \\/ WhatsApp\",\"FREE Edit 2 Files Pilihan\",\"Bisa Request Warna Background\",\"Pencahayaan studio standar profesional\"]', 'Foto Personal', 1, 'assets/paket-1791648684-294.jpg', '2026-10-07 02:10:46'),
(5, 'keluarga-paket-1', 'Foto Keluarga (All In)', 'keluarga', 'Foto Keluarga', 200000, 'Mulai Rp 200.000', '± 30 Menit Sesi', 'Keluarga Inti hingga Keluarga Besar', 'Sesi foto keluarga hangat di studio dengan arahan pose fotografer dan penataan komposisi bertingkat.', '[\"3-5 Orang: Rp 200.000\",\"6-10 Orang: Rp 300.000\",\"11-20 Orang: Rp 500.000\",\"1 Background & 1 Kostum (Kostum Sendiri)\",\"FREE ALL FILE Tanpa Biaya Tambahan\",\"FREE Edit ALL File (Color Preset & Correction)\",\"FREE CETAKAN 10R : 2 Lembar\"]', 'Keluarga Inti', 1, 'assets/foto-keluarga.jpg', '2026-10-07 02:10:46'),
(6, 'group-rombongan', 'Foto Group / Rombongan (All In)', 'group', 'Group & Rombongan', 100000, 'Mulai Rp 100.000', '30 - 60 Menit Sesi', 'Mulai 3 Orang hingga Rombongan Besar', 'Paket foto grup, angkatan, rekan kantor, atau squad organisasi. Harga disesuaikan dengan jumlah orang.', '[\"3-5 Orang: Rp 100.000\",\"6-10 Orang: Rp 150.000\",\"11-15 Orang: Rp 200.000\",\"16-20 Orang: Rp 250.000\",\">20 Orang: Silakan konsultasi\",\"1 Background & 1 Kostum\",\"FREE ALL Files via Google Drive\",\"FREE Edit Color Preset All Files\"]', 'Rombongan Angkatan', 1, 'assets/foto-group.jpg', '2026-10-07 02:10:46'),
(7, 'pas-foto-formal', 'Pas Foto Formal (File Only + Edit Standar)', 'formal', 'Pas Foto, Nikah & Ijazah', 25000, 'Rp 25.000', '± 10 Menit Sesi', '1 Orang', 'Pas foto resmi untuk lamaran kerja, visa, atau keperluan kedinasan kilat. Hasil rapi dan proporsional.', '[\"3 - 5x Foto (Bisa pilih sendiri file yang akan diedit)\",\"FREE Semua File Original (Asli)\",\"FREE Edit Standar 1 Foto Pilihan (Rapi, Cahaya, Lurus, Noda\\/Jerawat Mulus)\",\"Pilihan Warna Background (Merah, Biru, Putih)\",\"File dikirim via WhatsApp \\/ Google Drive (Belum termasuk cetak)\"]', 'Formal Kilat', 0, 'assets/paket-1791648726-863.jpg', '2026-10-07 02:10:46'),
(8, 'foto-ijazah', 'Foto Ijazah Jas / Almamater (+ Cetak 12 Lbr)', 'formal', 'Pas Foto, Nikah & Ijazah', 50000, 'Rp 50.000', '± 15 Menit Sesi', '1 Orang (Mahasiswa / Siswa)', 'Khusus foto ijazah UNM, UIN, POLTEKKES, UNIMERZ, STIKES, UNHAS, dll. Sudah termasuk peminjaman jas dan cetakan fisik standar ijazah.', '[\"FREE Pemakaian Jas Hitam \\/ Kemeja Putih \\/ Dasi\",\"3 - 5x Foto (Bebas pilih foto master)\",\"FREE Semua File Original dikirim via WA\",\"FREE EDIT FULL 1 Foto (Simetris, Mirror, Jerawat Hilang, Mulus)\",\"FREE Edit Ganti Background (Biru, Merah, Hitam, Putih)\",\"FREE CETAKAN 12 LEMBAR Kertas Foto Fujifilm Standar Ijazah (Doff\\/Glossy)\"]', 'Standar Kampus', 1, 'assets/paket-1791648718-893.jpg', '2026-10-07 02:10:46'),
(9, 'foto-ijazah-toga', 'Foto Ijazah Toga / Baju Wisuda', 'formal', 'Pas Foto, Nikah & Ijazah', 80000, 'Rp 80.000', '± 20 Menit Sesi', '1 Orang (Semua Kampus & Fakultas)', 'Foto ijazah resmi mengenakan toga & baju wisuda lengkap untuk seluruh kampus dan fakultas di Makassar.', '[\"FREE Pemakaian Baju & Toga Wisuda Lengkap\",\"3 - 5x Foto (Bisa pilih sendiri file yang diedit)\",\"FREE Semua File Original dikirim via WA\",\"FREE EDIT FULL 1 Foto Pilihan (Simetris, Mirror, Smooth Kulit)\",\"FREE Edit Jadi Hitam Putih (Jika disyaratkan kampus)\",\"FREE CETAKAN 12 LEMBAR Pinggiran Bergerigi Kertas Fujifilm Standar Ijazah (Doff\\/Glossy)\",\"Opsi Tambahan: Foto Bebas 1 Badan = +20K (Include Cetakan 10R)\"]', 'Toga Lengkap', 0, 'assets/paket-1791648708-682.jpg', '2026-10-07 02:10:46'),
(16, 'self-foto-kampus', 'Self foto kampus', 'self-photo', 'Self Photo', 30000, '30000/sesi', '20 menit', '2', 'Self foto', '[\"20 menit\"]', 'Self foto kampus', 0, 'assets/paket-1791696322-437.jpg', '2026-10-11 05:25:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_paket` (`kode_paket`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
