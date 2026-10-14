# Roadmap Pengembangan X-RENT

## Fase 1: Inisialisasi & Setup Arsitektur (Selesai)
- [x] **Task 1.1**: Daftarkan middleware `HandleInertiaRequests` di `bootstrap/app.php`.
- [x] **Task 1.2**: Konfigurasikan `vite.config.js` (`@vitejs/plugin-vue` & `@tailwindcss/vite`), `app.css`, `app.js`, dan root view `app.blade.php`.
- [x] **Task 1.3**: Buat `MainLayout.vue` untuk brand X-RENT (Navbar SPA, Footer garasi & jam operasional, Floating WhatsApp) dan `Pages/Home.vue`.

---

## Fase 2: Katalog Armada & Sistem Tarif Transparan (Selesai)
- [x] **Task 2.1**: Lengkapi seksi di bawah Hero pada `Pages/Home.vue` (Daftar Unit Unggulan dengan switch harga Harian/Mingguan/Bulanan, Syarat & Ketentuan Sewa, Lokasi Garasi, dan FAQ).
- [x] **Task 2.2**: Buat halaman `Pages/Catalog.vue` berdasarkan desain `stitch/screen-catalog.html` dan integrasikan ke menu Navbar menggunakan `<Link>` Inertia.
- [x] **Task 2.3**: Implementasikan filter kategori multi-tier, pencarian unit, toggle ketersediaan ("Hanya Tersedia"), pengurutan tarif (termurah/tertinggi), dan switch harga Harian/Mingguan/Bulanan secara reaktif menggunakan Vue 3 (`ref` & `computed`) dengan data dummy dari `CatalogController` / `HomeController`.

---

## Fase 3: Detail Unit Kendaraan & Kalkulator Sewa Cepat
- [ ] **Task 3.1**: Buat halaman `Pages/VehicleDetail.vue` mengacu pada `stitch/screen-detail.html`.
- [ ] **Task 3.2**: Galeri 4 sudut foto riil unit, spesifikasi mesin 160cc/kapasitas bagasi/fasilitas touring gratis (2 helm SNI + jas hujan).
- [ ] **Task 3.3**: Sticky booking calculator card dengan penyesuaian durasi (stepper hari/minggu), opsi ambil garasi vs antar-jemput bandara/hotel, dan ringkasan biaya pas tanpa biaya tersembunyi.

---

## Fase 4: Pelacakan Pesanan & Verifikasi Real-Time
- [ ] **Task 4.1**: Buat halaman `Pages/TrackBooking.vue` mengacu pada `stitch/screen-track.html`.
- [ ] **Task 4.2**: 4-Step visual progress stepper (Menunggu Konfirmasi -> Siap Diambil di Garasi -> Masa Sewa Berjalan -> Selesai & Pengembalian).
- [ ] **Task 4.3**: Formulir pencarian kode reservasi (XR-...) + WhatsApp pemesan, status verifikasi berkas (KTP & SIM), dan download voucher tanda terima.
