# Project Roadmap & Progress Tracker (X-RENT)

## Fase 1: Fondasi Laravel 13, Vue 3, & Vite
- [x] **Task 1.1:** Setup `bootstrap/app.php` (Inertia middleware), `vite.config.js` (Vue + Tailwind v4), `resources/css/app.css`, `resources/js/app.js`, dan `resources/views/app.blade.php`.
- [x] **Task 1.2:** Buat `Layouts/MainLayout.vue` (Navbar responsif X-RENT, Footer alamat & jam operasional, serta tombol Floating WhatsApp) dan route awal ke `Pages/Home.vue`.

## Fase 2: Desain Frontend Vue (Dengan Dummy Data di Controller)
- [x] **Task 2.1:** Buat `Components/VehicleCard.vue` (Menampilkan foto motor, CC, jumlah helm, kondisi umum, badge ketersediaan, dan tombol switch harga Harian/Mingguan/Bulanan).
- [x] **Task 2.2:** Lengkapi `Pages/Home.vue` (Hero section X-RENT, Katalog Pilihan Motor, Seksi Persyaratan Penyewaan [SIM C, KTP/KTM, Deposit, Denda], Lokasi Google Maps, dan `Components/FaqAccordion.vue`).
- [x] **Task 2.3:** Buat `Pages/Catalog.vue` beserta `VehicleController@index` (Filter instan tanpa reload berdasarkan Kategori Motor, Transmisi, Ketersediaan, dan Harga).
- [x] **Task 2.4:** Buat `Pages/VehicleDetail.vue` beserta `VehicleController@show` (Detail kondisi unit, Kalkulator Harga Reaktif termasuk opsi Antar-Jemput, dan Form Pemesanan Online).
- [x] **Task 2.5:** Buat `Pages/TrackBooking.vue` beserta `BookingController@track` (Input kode booking `XR-XXXX` dan visualisasi *Progress Stepper* status penyewaan).

## Fase 3: Integrasi Database MySQL & Backend Logic
- [x] **Task 3.1:** Buat Migration, Model, dan Seeder untuk `categories`, `vehicles`, `bookings`, dan 1 akun `Admin`.
- [x] **Task 3.2:** Hubungkan `HomeController` dan `VehicleController` dengan data asli dari database MySQL.
- [x] **Task 3.3:** Implementasi `BookingController@store` (Validasi form, simpan ke tabel `bookings`, generate kode `XR-YYYYMMDD-XXXX`, dan *redirect* link konfirmasi ke WhatsApp Admin).
- [x] **Task 3.4:** Hubungkan pencarian kode booking di `TrackBooking.vue` dengan tabel `bookings`.

## Fase 4: Panel Admin Pengelola
- [ ] **Task 4.1:** Buat autentikasi Login Admin sederhana & `Layouts/AdminLayout.vue`.
- [ ] **Task 4.2:** Buat halaman Dashboard Admin untuk mengelola unit motor (ubah status Tersedia/Disewa/Servis) dan mengubah status pesanan pelanggan (`Menunggu Konfirmasi` -> `Diterima` -> `Sedang Disewa` -> `Selesai`).
