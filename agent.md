# AI Agent Development Guidelines & Context

## Metodologi UI-First
1. **Prioritaskan Tampilan & Interaktivitas Pengguna (UI-First)**:
   - Bangun UI terlebih dahulu menggunakan data dummy representatif yang dikirim dari controller ke komponen Inertia.
   - Jangan menunggu database table atau model selesai untuk memvalidasi alur antarmuka.
   - Pastikan setiap elemen interaktif (filter kategori, tab harga harian/mingguan/bulanan, switcher lepas kunci/driver, modal detail, drawer mobile) berfungsi mulus.

2. **Konsistensi Desain**:
   - Selalu berpedoman pada `skills.md` dan `stitch/screen-1.html` untuk pemilihan warna, font (`Plus Jakarta Sans` & `Inter`), border radius (`rounded-2xl`, `rounded-xl`), dan spacing.
   - Gunakan nama token semantic Tailwind CSS v4 (`bg-primary`, `bg-surface`, `bg-tertiary-container`, dsb.).

3. **Inertia & Vue 3 Standards**:
   - Gunakan `<script setup>` di seluruh komponen Vue 3.
   - Pisahkan komponen yang berulang (misal `Components/VehicleCard.vue`) agar modular dan mudah dirawat.
   - Simpan dummy data terstruktur di `HomeController` untuk diteruskan sebagai props ke `Home.vue`.
