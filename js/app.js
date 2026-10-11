// Script utama Smart Studio

$(function () {
  "use strict";

  // Inisialisasi elemen
  const $window = $(window);
  const $header = $(".site-header");
  const $mobileToggle = $("#mobile-toggle");
  const $navMenu = $("#nav-menu");
  const $topNotification = $("#top-notification");
  const $closeNotifBtn = $("#close-notif-btn");
  const $katalogContainer = $("#katalog-grid");
  const $katalogLoading = $("#katalog-loading");
  const $katalogEmpty = $("#katalog-empty");
  const $katalogToggleWrap = $("#katalog-toggle-wrap");
  const $btnToggleKatalog = $("#btn-toggle-katalog");
  const $searchPaketInput = $("#cari-paket");
  const $paketFilterBtns = $(".filter-btn");
  const $galleryContainer = $("#gallery-grid");
  const $galleryFilterBtns = $(".gallery-filter-btn");
  const $lightboxModal = $("#lightbox-modal");
  const $lightboxImg = $("#lightbox-img");
  const $lightboxTitle = $("#lightbox-title");
  const $lightboxTag = $("#lightbox-tag");
  const $lightboxDesc = $("#lightbox-desc");
  const $lightboxClose = $("#lightbox-close");
  const $faqItems = $(".faq-item");

  let paketDatabase = [];
  let galeriDatabase = [];
  let isKatalogExpanded = false;
  const INITIAL_PAKET_LIMIT = 6;

  // Notifikasi header
  if ($closeNotifBtn.length) {
    $closeNotifBtn.on("click", function () {
      $topNotification.stop().slideUp(300);
    });
  }

  // Progress bar scroll
  const $progressBar = $("#scroll-progress-bar");

  function updateScrollProgress() {
    if (!$progressBar.length) return;
    const scrollTop = $window.scrollTop();
    const docHeight = $(document).height() - $window.height();
    if (docHeight > 0) {
      const scrollPercent = (scrollTop / docHeight) * 100;
      $progressBar.css("width", Math.min(100, Math.max(0, scrollPercent)) + "%");
    }
  }

  const $backToTop = $('<div id="back-to-top" title="Kembali ke atas"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg></div>');
  $("body").append($backToTop);

  $backToTop.on("click", function() {
    $("html, body").stop(true, true).animate({ scrollTop: 0 }, 600, "swing");
  });

  // Animasi scroll reveal
  $("section, .footer-bottom, .hero-content, .hero-photo-composition").addClass("reveal");
  
  function checkReveal() {
    const windowHeight = $window.height();
    const scrollY = $window.scrollTop();
    
    $(".reveal").each(function() {
      const elementTop = $(this).offset().top;
      if (scrollY + windowHeight > elementTop + 80) {
        $(this).addClass("active");
      }
    });

    if (scrollY > 500) {
      $backToTop.addClass("show");
    } else {
      $backToTop.removeClass("show");
    }
  }
  
  setTimeout(checkReveal, 100);

  $window.on("scroll", function () {
    updateScrollProgress();
    checkReveal();
    if ($(this).scrollTop() > 60) {
      $header.addClass("scrolled");
    } else {
      $header.removeClass("scrolled");
    }
  });

  updateScrollProgress();
  checkReveal();

  // Toggle menu mobile
  $mobileToggle.on("click", function () {
    $navMenu.toggleClass("open");
  });

  $navMenu.on("click", ".nav-link", function () {
    $navMenu.removeClass("open");
  });

  // Navigasi mobile
  $(".mobile-bottom-nav").on("click", ".mobile-nav-item:not(.wa-highlight)", function () {
    $(this).addClass("active").siblings().removeClass("active");
  });

  // Data paket fallback
  const FALLBACK_PAKET = [
  {
    "id": "self-photo-remote-25k",
    "nama": "Self Photo Remote (Hanya 25K / Orang)",
    "kategori": "self-photo",
    "kategoriLabel": "Self Photo",
    "harga": 25000,
    "hargaFormat": "Rp 25.000 / Orang",
    "durasi": "1 Sesi = 30 Menit Foto",
    "kapasitas": "Minimal 2 Orang \u2014 Maksimal 10 Orang",
    "deskripsi": "Pricelist baru self-photo mandiri dengan remote klik nirkabel. 1 sesi 30 menit puas, bebas berekspresi tanpa rasa canggung.",
    "fasilitas": [
      "Hanya Rp 25.000 / Orang (Minimal 2 orang / Maksimal 10 orang)",
      "1 Sesi = Durasi Panjang 30 Menit Foto Bebas Jepret Sepuasnya",
      "File Only (Tanpa cetakan, opsi tambah cetak 4R: 5k & 10R: 15k)",
      "FREE ALL Files via Google Drive / Flashdisk / HP Android",
      "FREE Edit Color Grading (Optional / Sesuai Permintaan)",
      "1 Sesi = 1 Pilihan Warna Background (Tambah Background = Tambah Sesi)",
      "Bebas Ganti Kostum (*Durasi waktu sesi tetap berjalan)",
      "Bebas Penggunaan Aksesoris Studio (Kacamata, Kursi, Bunga, dll)",
      "Privasi Terjamin: Bisa request jika foto tidak ingin diupload/publish"
    ],
    "tag": "Pricelist Baru 25K",
    "rekomendasi": true,
    "gambar": "assets/self-foto.jpg"
  },
  {
    "id": "wisuda-promo",
    "nama": "Paket Foto Wisuda (Promo Graduation)",
    "kategori": "wisuda",
    "kategoriLabel": "Wisuda",
    "harga": 200000,
    "hargaFormat": "Rp 200.000",
    "durasi": "\u00b1 30 Menit Sesi",
    "kapasitas": "Maksimal 5 Orang (Lebih dari 5 org +10k/org)",
    "deskripsi": "Paket foto wisuda lengkap bersama fotografer profesional. Abadikan momen kelulusan sendiri dan bersama keluarga (Maksimal 5 Orang, jika lebih +10rb/orang).",
    "fasilitas": [
      "40 - 60 Jepretan Foto",
      "FREE Cetak Foto 10R = 2 Lembar",
      "FREE ALL FILE via Link Google Drive",
      "FREE Edit Color Preset / Color Correction",
      "Foto Sendiri (Pakai & Lepas Toga)",
      "Foto Bertiga dengan Orang Tua",
      "Foto Berdua dengan Bapak, lalu Ibu",
      "Foto bersama Keluarga Inti",
      "Maksimal 5 Orang (+Rp 10.000/Orang tambahan)",
      "1 Background / Latar & 1 Kostum"
    ],
    "tag": "Promo Wisuda Favorit",
    "rekomendasi": true,
    "gambar": "assets/wisuda-sendiri.jpg"
  },
  {
    "id": "pas-foto-nikah",
    "nama": "Pas Foto Buku Nikah (KUA / Pasangan)",
    "kategori": "formal",
    "kategoriLabel": "Pas Foto, Nikah & Ijazah",
    "harga": 50000,
    "hargaFormat": "Rp 50.000 / Orang",
    "durasi": "\u00b1 15 Menit Sesi",
    "kapasitas": "Calon Pengantin (1 Orang / Sepasang)",
    "deskripsi": "Khusus pas foto resmi buku nikah KUA. Hasil simetris rapi sesuai standar resmi Kemenag RI.",
    "fasilitas": [
      "FREE Cetakan 12 Lembar Kertas Foto Standar Buku Nikah",
      "JIKA BERSAMA PASANGAN: FREE Foto Gandeng / Berdampingan 3-4 Kali Pose",
      "FREE Edit Simetris, Proporsional, & Bersih Mulus",
      "FREE Semua File Original dikirim via WA / Google Drive",
      "Pilihan Ganti Background Biru atau Merah Standar KUA"
    ],
    "tag": "Buku Nikah KUA",
    "rekomendasi": true,
    "gambar": "assets/foto-nikah-gandeng.jpg"
  },
  {
    "id": "foto-personal-50k",
    "nama": "Foto Personal \u2014 Formal & Non-Formal",
    "kategori": "formal",
    "kategoriLabel": "Pas Foto, Nikah & Ijazah",
    "harga": 50000,
    "hargaFormat": "Rp 50.000",
    "durasi": "\u00b1 15 Menit Sesi",
    "kapasitas": "1 Orang",
    "deskripsi": "Paket foto personal untuk lamaran kerja, profil LinkedIn, administrasi kantor, atau kebutuhan branding diri.",
    "fasilitas": [
      "3 - 5 Pose / Gaya (Pas Foto Formal, Close Up, Semi Formal, Full Body)",
      "FREE ALL Ori Files via Google Drive / WhatsApp",
      "FREE Edit 2 Files Pilihan",
      "Bisa Request Warna Background",
      "Pencahayaan studio standar profesional"
    ],
    "tag": "Foto Personal",
    "rekomendasi": true,
    "gambar": "assets/foto-personal-fb-1.jpg"
  },
  {
    "id": "keluarga-paket-1",
    "nama": "Foto Keluarga (All In)",
    "kategori": "keluarga",
    "kategoriLabel": "Foto Keluarga",
    "harga": 200000,
    "hargaFormat": "Mulai Rp 200.000",
    "durasi": "\u00b1 30 Menit Sesi",
    "kapasitas": "Keluarga Inti hingga Keluarga Besar",
    "deskripsi": "Sesi foto keluarga hangat di studio dengan arahan pose fotografer dan penataan komposisi bertingkat.",
    "fasilitas": [
      "3-5 Orang: Rp 200.000",
      "6-10 Orang: Rp 300.000",
      "11-20 Orang: Rp 500.000",
      "1 Background & 1 Kostum (Kostum Sendiri)",
      "FREE ALL FILE Tanpa Biaya Tambahan",
      "FREE Edit ALL File (Color Preset & Correction)",
      "FREE CETAKAN 10R : 2 Lembar"
    ],
    "tag": "Keluarga Inti",
    "rekomendasi": true,
    "gambar": "assets/foto-keluarga.jpg"
  },
  {
    "id": "group-rombongan",
    "nama": "Foto Group / Rombongan (All In)",
    "kategori": "group",
    "kategoriLabel": "Group & Rombongan",
    "harga": 100000,
    "hargaFormat": "Mulai Rp 100.000",
    "durasi": "30 - 60 Menit Sesi",
    "kapasitas": "Mulai 3 Orang hingga Rombongan Besar",
    "deskripsi": "Paket foto grup, angkatan, rekan kantor, atau squad organisasi. Harga disesuaikan dengan jumlah orang.",
    "fasilitas": [
      "3-5 Orang: Rp 100.000",
      "6-10 Orang: Rp 150.000",
      "11-15 Orang: Rp 200.000",
      "16-20 Orang: Rp 250.000",
      ">20 Orang: Silakan konsultasi",
      "1 Background & 1 Kostum",
      "FREE ALL Files via Google Drive",
      "FREE Edit Color Preset All Files"
    ],
    "tag": "Rombongan Angkatan",
    "rekomendasi": true,
    "gambar": "assets/foto-group.jpg"
  },
  {
    "id": "pas-foto-formal",
    "nama": "Pas Foto Formal (File Only + Edit Standar)",
    "kategori": "formal",
    "kategoriLabel": "Pas Foto, Nikah & Ijazah",
    "harga": 25000,
    "hargaFormat": "Rp 25.000",
    "durasi": "\u00b1 10 Menit Sesi",
    "kapasitas": "1 Orang",
    "deskripsi": "Pas foto resmi untuk lamaran kerja, visa, atau keperluan kedinasan kilat. Hasil rapi dan proporsional.",
    "fasilitas": [
      "3 - 5x Foto (Bisa pilih sendiri file yang akan diedit)",
      "FREE Semua File Original (Asli)",
      "FREE Edit Standar 1 Foto Pilihan (Rapi, Cahaya, Lurus, Noda/Jerawat Mulus)",
      "Pilihan Warna Background (Merah, Biru, Putih)",
      "File dikirim via WhatsApp / Google Drive (Belum termasuk cetak)"
    ],
    "tag": "Formal Kilat",
    "rekomendasi": false,
    "gambar": "assets/foto-personal-stengah-badan.jpg"
  },
  {
    "id": "foto-ijazah",
    "nama": "Foto Ijazah Jas / Almamater (+ Cetak 12 Lbr)",
    "kategori": "formal",
    "kategoriLabel": "Pas Foto, Nikah & Ijazah",
    "harga": 50000,
    "hargaFormat": "Rp 50.000",
    "durasi": "\u00b1 15 Menit Sesi",
    "kapasitas": "1 Orang (Mahasiswa / Siswa)",
    "deskripsi": "Khusus foto ijazah UNM, UIN, POLTEKKES, UNIMERZ, STIKES, UNHAS, dll. Sudah termasuk peminjaman jas dan cetakan fisik standar ijazah.",
    "fasilitas": [
      "FREE Pemakaian Jas Hitam / Kemeja Putih / Dasi",
      "3 - 5x Foto (Bebas pilih foto master)",
      "FREE Semua File Original dikirim via WA",
      "FREE EDIT FULL 1 Foto (Simetris, Mirror, Jerawat Hilang, Mulus)",
      "FREE Edit Ganti Background (Biru, Merah, Hitam, Putih)",
      "FREE CETAKAN 12 LEMBAR Kertas Foto Fujifilm Standar Ijazah (Doff/Glossy)"
    ],
    "tag": "Standar Kampus",
    "rekomendasi": true,
    "gambar": "assets/foto-ijazah-jas.jpg"
  },
  {
    "id": "foto-ijazah-toga",
    "nama": "Foto Ijazah Toga / Baju Wisuda",
    "kategori": "formal",
    "kategoriLabel": "Pas Foto, Nikah & Ijazah",
    "harga": 80000,
    "hargaFormat": "Rp 80.000",
    "durasi": "\u00b1 20 Menit Sesi",
    "kapasitas": "1 Orang (Semua Kampus & Fakultas)",
    "deskripsi": "Foto ijazah resmi mengenakan toga & baju wisuda lengkap untuk seluruh kampus dan fakultas di Makassar.",
    "fasilitas": [
      "FREE Pemakaian Baju & Toga Wisuda Lengkap",
      "3 - 5x Foto (Bisa pilih sendiri file yang diedit)",
      "FREE Semua File Original dikirim via WA",
      "FREE EDIT FULL 1 Foto Pilihan (Simetris, Mirror, Smooth Kulit)",
      "FREE Edit Jadi Hitam Putih (Jika disyaratkan kampus)",
      "FREE CETAKAN 12 LEMBAR Pinggiran Bergerigi Kertas Fujifilm Standar Ijazah (Doff/Glossy)",
      "Opsi Tambahan: Foto Bebas 1 Badan = +20K (Include Cetakan 10R)"
    ],
    "tag": "Toga Lengkap",
    "rekomendasi": false,
    "gambar": "assets/foto-ijazah-toga-1.jpg"
  }
];

  // Load data paket via AJAX
  function loadPaketData() {
    $.ajax({
      url: "data/paket.json",
      method: "GET",
      dataType: "json"
    })
      .done(function (data) {
        paketDatabase = data;
        renderPaketCards(paketDatabase);
      })
      .fail(function (xhr, status, error) {
        console.warn("Gagal memuat paket:", error);
        paketDatabase = FALLBACK_PAKET;
        renderPaketCards(paketDatabase);
      })
      .always(function () {
        $katalogLoading.hide();
      });
  }

  // Render kartu paket
  function renderPaketCards(items) {
    $katalogContainer.empty();

    if (!items || items.length === 0) {
      $katalogEmpty.show();
      return;
    }
    $katalogEmpty.hide();

    $.each(items, function (idx, item) {
      const $card = $("<div></div>")
        .addClass("package-card")
        .attr("data-kategori", item.kategori)
        .attr("data-id", item.id);

      const $thumb = $("<div></div>").addClass("package-thumb");
      const $img = $("<img>")
        .attr("src", item.gambar)
        .attr("alt", item.nama)
        .attr("loading", "lazy");
      $thumb.append($img).attr("tabindex", "0").attr("role", "button").attr("aria-label", "Lihat foto " + item.nama);

      const $body = $("<div></div>").addClass("package-body");
      const $cat = $("<div></div>").addClass("package-category").text(item.kategoriLabel);
      const $title = $("<h4></h4>").addClass("package-title").text(item.nama);

      const $priceBox = $("<div></div>").addClass("package-price-box");
      const $price = $("<span></span>").addClass("package-price").text(item.hargaFormat);
      const $time = $("<span></span>").addClass("package-session-time").text("• " + item.durasi);
      $priceBox.append($price, $time);

      const $desc = $("<p></p>").addClass("package-desc").text(item.deskripsi);

      const $features = $("<ul></ul>").addClass("package-features");
      $.each(item.fasilitas, function (_, fas) {
        $features.append($("<li></li>").text(fas));
      });

      const $footer = $("<div></div>").addClass("package-footer");
      const pesanWA = 
        "Halo Admin Smart Studio (@smartstudio.id_)!\n" +
        "Saya ingin bertanya / reservasi sesi foto:\n\n" +
        "• Paket: " + item.nama + "\n" +
        "• Kategori: " + item.kategoriLabel + "\n" +
        "• Biaya: " + item.hargaFormat + " (" + item.durasi + ")\n\n" +
        "Apakah masih tersedia slot jadwal? Terima kasih!";
      
      const waUrl = "https://api.whatsapp.com/send?phone=628115038011&text=" + encodeURIComponent(pesanWA);

      const $btn = $("<a></a>")
        .addClass("btn-select-package")
        .attr("href", waUrl)
        .attr("target", "_blank")
        .attr("rel", "noopener noreferrer")
        .html('<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: text-bottom; margin-right: 6px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg> Booking via WhatsApp &rarr;');

      $footer.append($btn);
      $body.append($cat, $title, $priceBox, $desc, $features, $footer);
      $card.append($thumb, $body);

      $katalogContainer.append($card);
    });

    filterPaketCatalog();
  }

  // Filter dan pencarian paket 
  function filterPaketCatalog() {
    const keyword = $searchPaketInput.val().toLowerCase().trim();
    const activeCategory = $paketFilterBtns.filter(".active").attr("data-filter");

    const $allCards = $(".package-card");
    let matchingCards = [];

    $allCards.each(function () {
      const $card = $(this);
      const textContent = $card.text().toLowerCase();
      const cardCategory = $card.attr("data-kategori");

      const matchesSearch = keyword === "" || textContent.indexOf(keyword) > -1;
      const matchesCategory = activeCategory === "all" || cardCategory === activeCategory;

      if (matchesSearch && matchesCategory) {
        matchingCards.push($card);
      } else {
        $card.hide();
      }
    });

    const isDefaultAll = (activeCategory === "all" && keyword === "");

    if (isDefaultAll) {
      if (matchingCards.length > INITIAL_PAKET_LIMIT) {
        $katalogToggleWrap.show();
        if (isKatalogExpanded) {
          $.each(matchingCards, function (_, $c) {
            $c.show();
          });
          $btnToggleKatalog.addClass("expanded")
            .find(".toggle-text").text("Tampilkan Lebih Sedikit ▴");
        } else {
          $.each(matchingCards, function (i, $c) {
            if (i < INITIAL_PAKET_LIMIT) {
              $c.show();
            } else {
              $c.hide();
            }
          });
          $btnToggleKatalog.removeClass("expanded")
            .find(".toggle-text").text("Lihat Semua Paket (" + matchingCards.length + " Pilihan Layanan) ▾");
        }
      } else {
        $katalogToggleWrap.hide();
        $.each(matchingCards, function (_, $c) {
          $c.show();
        });
      }
    } else {
      $katalogToggleWrap.hide();
      $.each(matchingCards, function (_, $c) {
        $c.show();
      });
    }

    $katalogEmpty.toggle(matchingCards.length === 0);
  }

  // Modal lightbox paket
  $katalogContainer.on("click keypress", ".package-thumb", function (e) {
    if (e.type === "keypress" && e.which !== 13) return;
    const $card = $(this).closest(".package-card");
    const itemId = $card.attr("data-id");
    const item = paketDatabase.find(p => p.id == itemId);
    if (item) {
      $("#lightbox-img").attr("src", item.gambar);
      $("#lightbox-tag").text(item.kategoriLabel);
      $("#lightbox-title").text(item.nama);
      $("#lightbox-desc").text(item.deskripsi);
      $("#lightbox-modal").stop(true, true).css("display", "flex").hide().fadeIn(300);
    }
  });

  $searchPaketInput.on("keyup", function () {
    filterPaketCatalog();
  });

  $paketFilterBtns.on("click", function (e) {
    e.preventDefault();
    $(this).addClass("active").siblings().removeClass("active");
    filterPaketCatalog();
  });

  // Toggle katalog paket
  $btnToggleKatalog.on("click", function () {
    isKatalogExpanded = !isKatalogExpanded;

    if (isKatalogExpanded) {
      $(".package-card:hidden").stop(true, true).fadeIn(350);
      $btnToggleKatalog.addClass("expanded")
        .find(".toggle-text").text("Tampilkan Lebih Sedikit ▴");
    } else {
      $(".package-card").each(function (i) {
        if (i >= INITIAL_PAKET_LIMIT) {
          $(this).stop(true, true).slideUp(250);
        }
      });
      $btnToggleKatalog.removeClass("expanded")
        .find(".toggle-text").text("Lihat Semua Paket (" + $(".package-card").length + " Pilihan Layanan) ▾");

      $("html, body").animate({
        scrollTop: $("#katalog").offset().top - 80
      }, 400);
    }
  });

  // Data galeri fallback
  const FALLBACK_GALERI = [
  {
    "id": 1,
    "judul": "Wisuda Sarjana \u2014 Warm Editorial",
    "kategori": "wisuda",
    "kategoriLabel": "Wisuda",
    "tahun": "2024",
    "lokasi": "Studio 1 (Warm Ivory)",
    "deskripsi": "Pose wisuda formal namun tetap rileks dengan penataan key light lembut dan backdrop warm cream.",
    "gambar": "assets/wisuda-sendiri.jpg"
  },
  {
    "id": 2,
    "judul": "Self Photo \u2014 Minimalist Duo",
    "kategori": "self-photo",
    "kategoriLabel": "Self Photo",
    "tahun": "2024",
    "lokasi": "Self Studio Room B",
    "deskripsi": "Sesi kasual sepasang sahabat dengan remote klik mandiri berlatar abu-abu netral.",
    "gambar": "assets/self-foto.jpg"
  },
  {
    "id": 3,
    "judul": "Buku Tahunan Sekolah",
    "kategori": "yearbook",
    "kategoriLabel": "Yearbook",
    "tahun": "2024",
    "lokasi": "Mini Studio Concept",
    "deskripsi": "Nuansa hangat dan intim dengan pencahayaan rim-light lembut bernuansa earth-tone.",
    "gambar": "assets/yearbook.jpg"
  },
  {
    "id": 4,
    "judul": "Geng Kampus Squad Portrait",
    "kategori": "group",
    "kategoriLabel": "Group",
    "tahun": "2024",
    "lokasi": "Main Studio Hall",
    "deskripsi": "Foto grup sahabat kuliah penuh tawa dan keakraban dengan properti stool kayu.",
    "gambar": "assets/foto-group.jpg"
  },
  {
    "id": 5,
    "judul": "Wisuda Bersama Keluarga",
    "kategori": "wisuda",
    "kategoriLabel": "Wisuda",
    "tahun": "2024",
    "lokasi": "Studio 1 (Classic)",
    "deskripsi": "Momen penuh haru dan bangga orang tua merayakan kelulusan sang putri.",
    "gambar": "assets/foto-wisuda-keluarga.jpg"
  },
  {
    "id": 6,
    "judul": "Family Milestone Portrait",
    "kategori": "family",
    "kategoriLabel": "Family",
    "tahun": "2024",
    "lokasi": "Main Studio Hall",
    "deskripsi": "Potret keluarga 3 generasi dengan tone hangat dan tata komposisi seimbang.",
    "gambar": "assets/foto-keluarga.jpg"
  },
  {
    "id": 7,
    "judul": "Pas Foto Nikah (Wanita)",
    "kategori": "formal",
    "kategoriLabel": "Formal",
    "tahun": "2024",
    "lokasi": "Studio 2",
    "deskripsi": "Pas foto standar buku nikah KUA dengan background bersih.",
    "gambar": "assets/foto-nikah-cewe.jpg"
  },
  {
    "id": 8,
    "judul": "Pas Foto Nikah (Pria)",
    "kategori": "formal",
    "kategoriLabel": "Formal",
    "tahun": "2024",
    "lokasi": "Studio 2",
    "deskripsi": "Pas foto standar buku nikah KUA pria dengan proporsi rapi.",
    "gambar": "assets/foto-nikah-cowo.jpg"
  }
];

  // Load data galeri via AJAX
  function loadGaleriData() {
    $.ajax({
      url: "data/galeri.json",
      method: "GET",
      dataType: "json"
    })
      .done(function (data) {
        galeriDatabase = data;
        renderGalleryGrid(galeriDatabase);
      })
      .fail(function (xhr, status, error) {
        console.warn("Gagal memuat galeri:", error);
        galeriDatabase = FALLBACK_GALERI;
        renderGalleryGrid(galeriDatabase);
      });
  }

  function renderGalleryGrid(items) {
    $galleryContainer.empty();

    $.each(items, function (_, item) {
      const $item = $("<div></div>")
        .addClass("gallery-item")
        .attr("data-kategori", item.kategori)
        .attr("data-id", item.id);

      const $img = $("<img>")
        .attr("src", item.gambar)
        .attr("alt", item.judul)
        .attr("loading", "lazy");

      const $overlay = $("<div></div>").addClass("gallery-overlay");
      const $tag = $("<span></span>").addClass("gallery-overlay-tag").text(item.kategoriLabel);
      const $title = $("<h4></h4>").addClass("gallery-overlay-title").text(item.judul);
      const $loc = $("<span></span>").addClass("gallery-overlay-loc").text(item.lokasi);

      $overlay.append($tag, $title, $loc);
      $item.append($img, $overlay).attr("tabindex", "0").attr("role", "button").attr("aria-label", "Lihat karya " + (item.judul || item.kategoriLabel));
      $galleryContainer.append($item);
    });
  }

  // Filter galeri
  $galleryFilterBtns.on("click", function (e) {
    e.preventDefault();
    $(this).addClass("active").siblings().removeClass("active");

    const filterVal = $(this).attr("data-filter");

    $(".gallery-item").each(function () {
      const itemKategori = $(this).attr("data-kategori");
      const shouldShow = filterVal === "all" || itemKategori === filterVal;
      $(this).toggle(shouldShow);
    });
  });

  // Modal lightbox galeri
  $galleryContainer.on("click", ".gallery-item", function () {
    const itemId = parseInt($(this).attr("data-id"), 10);
    const photoData = galeriDatabase.find(function (g) {
      return g.id === itemId;
    });

    if (photoData) {
      $lightboxImg.attr("src", photoData.gambar);
      $lightboxTag.text(photoData.kategoriLabel);
      $lightboxTitle.text(photoData.judul);
      $lightboxDesc.text(photoData.deskripsi + " (" + photoData.lokasi + ")");

      $lightboxModal.stop(true, true).css("display", "flex").hide().fadeIn(300);
      $("body").css("overflow", "hidden");
    }
  });

  function closeLightbox() {
    $lightboxModal.stop(true, true).fadeOut(250, function () {
      $("body").css("overflow", "auto");
    });
  }

  $lightboxClose.on("click keypress", function (e) {
    if (e.type === "keypress" && e.which !== 13) return;
    closeLightbox();
  });

  $lightboxModal.on("click", function (e) {
    if ($(e.target).is("#lightbox-modal")) {
      closeLightbox();
    }
  });

  $(document).on("keydown", function (e) {
    if (e.key === "Escape" && $lightboxModal.is(":visible")) {
      closeLightbox();
    }
  });

  // FAQ accordion
  $(".faq-list").on("click", ".faq-trigger", function () {
    const $parentItem = $(this).closest(".faq-item");
    const $content = $parentItem.find(".faq-content");
    const isOpen = $parentItem.hasClass("active");

    $parentItem.siblings().removeClass("active").find(".faq-content").stop(true, true).slideUp(300);

    if (isOpen) {
      $parentItem.removeClass("active");
      $content.stop(true, true).slideUp(300);
    } else {
      $parentItem.addClass("active");
      $content.stop(true, true).slideDown(300);
    }
  });

  // Salin alamat studio
  function showToast(message) {
    const $toast = $("#toast-notification");
    $toast.text(message).stop(true, true).fadeIn(300).delay(2800).fadeOut(400);
  }

  $("#btn-copy-address").on("click", function () {
    const textToCopy = 
      "SMART STUDIO MAKASSAR\n" +
      "Alamat: Jl. Poros Sultan Alauddin No. Ruko 259 F, Mangasa, Kec. Tamalate, Kota Makassar\n" +
      "Patokan: 50m setelah gerbang keluar UNISMUH arah Gowa, samping Halte Bus & Indomaret (seberang Mie Gacoan Alauddin)\n" +
      "Titik Ojol: SMART PHOTOGRAPHY\n" +
      "Maps: https://maps.app.goo.gl/95Ex56zstoqCJave7";

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(textToCopy).then(function () {
        showToast("✓ Alamat & patokan studio disalin! Siap ditempel di Grab/Gojek/WA.");
      }).catch(function () {
        showToast("📍 Titik Ojol: SMART PHOTOGRAPHY (50m dari Unismuh)");
      });
    } else {
      showToast("📍 Titik Ojol: SMART PHOTOGRAPHY (50m dari Unismuh)");
    }
  });

  // Inisialisasi data
  loadPaketData();
  loadGaleriData();

});

  // Preloader
  $(window).on("load", function() {
    if (sessionStorage.getItem('splashSeen') !== 'true') {
      setTimeout(function() {
        $("body").addClass("loaded");
        sessionStorage.setItem('splashSeen', 'true');
      }, 700);
    }
  });
