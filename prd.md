# Product Requirements Document (PRD) - X-RENT

## 1. Overview Produk
**X-RENT** adalah platform rental kendaraan (mobil dan motor) modern berbasis web yang melayani kebutuhan sewa harian, mingguan, bulanan, lepas kunci (*self-drive*), maupun dengan supir profesional (*chauffeur*).

---

## 2. Fitur-Fitur Utama (Core Features)

### 2.1 Katalog Armada (Mobil & Motor)
- **Tampilan Grid & Filter**: Filter berdasarkan kategori (City Car, Family MPV, Premium SUV, HiAce/Elf, Motor Matic, Motor Retro).
- **Badge Status Ketersediaan**:
  - `Tersedia Hari Ini` (Warna hijau mint/tertiary, dengan indikator dot aktif)
  - `Sedang Disewa` / `Booked` (Warna amber/slate, tombol sewa menyesuaikan tanggal berikutnya)
- **Tab Periode Harga Interaktif**:
  - Pilihan periode: **Harian**, **Mingguan** (diskon hemat ~15%), **Bulanan** (diskon hemat ~30%).
  - Harga ter-update secara reaktif saat tab diklik pengguna.
- **Spesifikasi Teknis**:
  - Mobil: Kapasitas kursi, tipe transmisi (CVT/Manual), AC (Double blower/Dual zone), konsumsi BBM / mesin.
  - Motor: Kapasitas mesin (cc), perlengkapan (2 Helm SNI bersih, jas hujan ganda, holder HP waterproof), bagasi luas, rem ABS / Smart Key.

### 2.2 Sistem Quick Booking & Estimasi
- Form pencarian cepat: Pilihan Mobil/Motor, mode Lepas Kunci / Dengan Supir, dropdown lokasi pengambilan (Garasi Cilandak, Bandara Soetta, Stasiun, Antar Hotel), tanggal & jam mulai/selesai sewa.
- CTA langsung ke WhatsApp dengan *pre-filled message* terstruktur sesuai unit dan durasi yang dipilih.

### 2.3 Persyaratan Sewa Transparan
- **Sewa Mobil Lepas Kunci**: KTP asli, SIM A aktif, bukti akun sosmed aktif, deposit jaminan refundable.
- **Sewa Motor**: KTP asli, SIM C aktif, foto identitas penjamin/tiket perjalanan, deposit jaminan refundable.
- **Sewa Dengan Supir**: Cukup KTP pemesan & detail rute perjalanan (tanpa deposit unit).

### 2.4 FAQ & Layanan Pelanggan
- Accordion interaktif berisi jawaban atas pertanyaan yang paling sering diajukan (mekanisme deposit, kebijakan pembatalan, asuransi all-risk, antar-jemput luar kota).
- Floating WhatsApp Button 24/7 dengan status CS aktif.

### 2.5 Identitas Garasi & Pembayaran
- Alamat Garasi Pusat di Cilandak, Jakarta Selatan dan Cabang Bandara Soekarno-Hatta & BSD.
- Jam Operasional Garasi: 06.00 - 23.00 WIB, dengan CS Darurat 24 Jam Non-Stop.
- Dukungan metode pembayaran resmi: BCA, Mandiri, BRI, QRIS, Visa/MasterCard.
