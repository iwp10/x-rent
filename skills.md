# X-RENT Design System & Guidelines (Google Stitch Reference)

Dokumen ini merupakan panduan utama gaya visual (*Design System*), palet warna, tipografi, radius, dan pola komponen untuk aplikasi **X-RENT**, diadaptasi langsung dari desain UI Google Stitch (`stitch/screen-1.html`).

---

## 1. Color Palette (Material 3 Dynamic System)

Aplikasi menggunakan palet warna bernuansa korporat modern, segar, dan terpercaya dengan kontras tinggi (Accessible AAA/AA standard):

### Primary Brand Colors (Deep Royal Blue)
- **Primary**: `#0037b0` (Warna utama untuk tombol CTA, brand logo, highlight link aktif)
- **Primary Container**: `#1d4ed8` (Hover state & banner gradients)
- **Primary Fixed**: `#dce1ff` (Badge latar belakang kategori)
- **Primary Fixed Dim**: `#b7c4ff`
- **On Primary**: `#ffffff` (Teks di atas warna primary)
- **On Primary Container**: `#cad3ff`

### Secondary Colors (Slate Navy)
- **Secondary**: `#565e74` (Teks pendukung & badge netral)
- **Secondary Container**: `#dae2fd`
- **On Secondary Container**: `#5c647a`

### Tertiary / Success Colors (Emerald & Mint Eco)
- **Tertiary**: `#004f35` (Aksen hijau tua, badge ready, teks status tersedia)
- **Tertiary Container**: `#006948` (Ikon checklist, badge rekomendasi)
- **Tertiary Fixed**: `#85f8c4` (Aksen mint terang)
- **Tertiary Fixed Dim**: `#68dba9`
- **On Tertiary Container**: `#76eab6`

### Surface & Background Tones (Soft Slate Blue)
- **Background / Surface**: `#f8f9ff` (Latar belakang utama aplikasi, sejuk dan bersih)
- **Surface Container Lowest**: `#ffffff` (Kartu kendaraan, modal, kontainer utama)
- **Surface Container Low**: `#eff4ff` (Latar belakang strip hero, seksi secondary)
- **Surface Container**: `#e5eeff` (Latar belakang tab switch segment)
- **Surface Container High**: `#dce9ff` (Badge chip & tombol filter tidak aktif)
- **Surface Container Highest**: `#d3e4fe` (Garis pembatas & divider halus)
- **Surface Dim**: `#cbdbf5`

### Text & Outline Colors
- **On Surface**: `#0b1c30` (Teks judul utama, hampir hitam dengan rona navy)
- **On Surface Variant**: `#434655` (Teks paragraf, spesifikasi, dan label sekunder)
- **Outline**: `#747686` (Border form input saat fokus)
- **Outline Variant**: `#c4c5d7` (Border pembatas halus antar elemen)
- **Error**: `#ba1a1a` & **Error Container**: `#ffdad6` (Status dibatalkan / gagal)

---

## 2. Typography

Kombinasi 2 font Google Fonts:
1. **Plus Jakarta Sans**: Digunakan untuk *Headings* (`h1`, `h2`, `h3`), *Titles*, dan *Body Text* agar terlihat ramah, modern, dan profesional.
2. **Inter**: Digunakan untuk *Spec-data* (angka kapasitas, cc, tahun), *Numeric Labels* (harga, durasi), dan *Badges/Tags* untuk legibilitas maksimal.

### Skala Tipografi:
- **Display Hero**: 56px (line-height: 64px, weight: 800)
- **Headline Large**: 32px (line-height: 40px, weight: 700)
- **Headline Medium**: 24px (line-height: 32px, weight: 600)
- **Headline Small**: 20px (line-height: 28px, weight: 600)
- **Title Medium**: 16px (line-height: 24px, weight: 600)
- **Body Large**: 18px (line-height: 28px, weight: 400)
- **Body Medium**: 15px (line-height: 22px, weight: 400)
- **Body Small**: 13px (line-height: 18px, weight: 400)
- **Spec Data / Badge**: 11px - 12px (line-height: 16px, weight: 500/700, font: Inter)

---

## 3. Border Radius & Elevation (Shadows)

- **Cards & Modals**: `rounded-2xl` (16px) dengan `shadow-md` dan hover `hover:-translate-y-1 hover:shadow-xl`
- **Buttons & Input Fields**: `rounded-xl` (12px) dengan transisi `active:scale-95 duration-150`
- **Badges & Floating Actions**: `rounded-full`
- **Ambient Glows**: Menggunakan `bg-primary/10 blur-[130px]` dan `bg-tertiary-container/10 blur-[110px]` untuk kedalaman atmosfer modern.

---

## 4. Pola Komponen Inti

### A. Kartu Kendaraan (`Components/VehicleCard.vue`)
- **Foto Unit**: Aspect ratio 16:9 / height 48, dilengkapi badge status (*Tersedia Hari Ini* warna hijau mint vs *Sedang Disewa* warna amber/slate).
- **Tipe & Tahun**: Badge kecil (*MPV*, *City Car*, *SUV*, *Matic*) dan tahun unit.
- **Matrix Telemetri Spesifikasi**: 4 grid spesifikasi (Kapasitas kursi/orang, transmisi, AC/fitur, bahan bakar/cc) berlatar belakang `bg-surface-container-low`.
- **Tab Periode Harga Interaktif**: Tab switcher **Harian**, **Mingguan**, dan **Bulanan** dengan kalkulasi harga otomatis dan label diskon hemat.
- **Tombol Aksi**: Tombol *Detail Unit* (`bg-surface-container-high text-primary`) dan *Sewa Sekarang* (`bg-primary text-white`).

### B. Header & Navbar (`Layouts/MainLayout.vue`)
- **Top Bar**: Menampilkan info garasi (Cilandak & Soetta), nomor hotline, jam buka garasi (06.00 - 23.00 WIB), dan CS darurat 24 jam.
- **Brand Logo**: Logo X-RENT dengan icon/image mobil, font bold, dan badge 'Rental Kendaraan'.
- **Navigasi**: Beranda, Armada Mobil, Armada Motor, Paket Wisata & Supir, Syarat & Ketentuan, Cek Booking.
- **Tombol CS & Auth**: 'Hubungi CS' dan 'Masuk / Daftar'.

### C. Floating WhatsApp Button
- Terletak di posisi `fixed bottom-6 right-6 z-50`.
- Memiliki efek animasi pulse ring berulang, tooltip bantuan respons cepat 24 jam, dan link langsung ke nomor WhatsApp resmi X-RENT.

### D. Footer
- Informasi legal PT X-RENT Mobilitas Indonesia, alamat garasi pusat dan cabang, jam operasional, menu navigasi, dan logo metode pembayaran (BCA, Mandiri, BRI, QRIS, Visa/Mastercard).
