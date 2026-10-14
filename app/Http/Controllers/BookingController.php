<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * Display the Track Booking page (Task 2.5 roadmap.md).
     */
    public function track(Request $request): Response
    {
        $code = strtoupper(trim((string) $request->query('code', '')));
        $allBookings = self::getDummyBookings();

        // If code query param is provided, find matching booking
        $matchedBooking = null;
        if (!empty($code)) {
            foreach ($allBookings as $booking) {
                if (strtoupper($booking['code']) === $code) {
                    $matchedBooking = $booking;
                    break;
                }
            }
        }

        // Default to first booking (pending) if no code provided or for quick sample viewing
        $activeBooking = $matchedBooking ?: $allBookings[0];

        return Inertia::render('TrackBooking', [
            'initialCode' => $code,
            'activeBooking' => $activeBooking,
            'allBookings' => $allBookings,
            'sampleCodes' => [
                [
                    'code' => 'XR-20261014-AB12',
                    'status' => 'pending',
                    'label' => 'Pending (Menunggu)',
                    'badge' => 'Menunggu Konfirmasi',
                    'vehicle' => 'Honda Beat CBS',
                ],
                [
                    'code' => 'XR-20261014-CD34',
                    'status' => 'confirmed',
                    'label' => 'Confirmed (Siap Diambil)',
                    'badge' => 'Pesanan Diterima',
                    'vehicle' => 'Honda Brio RS',
                ],
                [
                    'code' => 'XR-20261014-EF56',
                    'status' => 'ongoing',
                    'label' => 'Ongoing (Sedang Disewa)',
                    'badge' => 'Sedang Berjalan',
                    'vehicle' => 'Toyota Avanza 1.5 G',
                ],
                [
                    'code' => 'XR-20261014-GH78',
                    'status' => 'completed',
                    'label' => 'Completed (Selesai)',
                    'badge' => 'Selesai & Refund',
                    'vehicle' => 'Yamaha NMAX 155',
                ],
            ],
            'faqs' => [
                [
                    'q' => 'Apakah bisa minta diantarkan ke bandara / stasiun / hotel?',
                    'a' => 'Bisa. Hubungi CS WhatsApp minimal 4 jam sebelum jadwal sewa untuk menambah layanan delivery drop-off unit langsung ke lokasi Anda.',
                ],
                [
                    'q' => 'Bagaimana jika saya terlambat mengembalikan kendaraan?',
                    'a' => 'X-RENT memberikan masa tenggang toleransi keterlambatan hingga 60 menit secara cuma-cuma tanpa denda overtime selama konfirmasi terlebih dahulu.',
                ],
                [
                    'q' => 'Kapan uang deposit jaminan dikembalikan?',
                    'a' => 'Deposit jaminan (Rp 50.000 untuk motor & Rp 200.000 untuk mobil) dikembalikan 100% saat serah terima unit kembali dalam kondisi aman dan bensin sesuai posisi awal.',
                ],
                [
                    'q' => 'Apakah harga sewa sudah termasuk asuransi?',
                    'a' => 'Ya, seluruh armada mobil & motor X-RENT sudah tercover proteksi asuransi dasar kerusakan mesin dan garansi unit pengganti gratis jika mogok di jalan.',
                ],
            ],
        ]);
    }

    /**
     * Simulate booking form submission from VehicleDetail.vue (Task 2.5 roadmap.md).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'customer_category' => 'nullable|string|max:50',
            'start_date' => 'nullable|string|max:50',
            'duration_type' => 'nullable|string|in:daily,weekly,monthly',
            'duration_count' => 'nullable|numeric|min:1',
            'pickup_option' => 'nullable|string|in:garage,delivery',
            'notes' => 'nullable|string|max:500',
            'vehicle_name' => 'nullable|string|max:100',
        ]);

        // Generate realistic transaction booking code
        $bookingCode = 'XR-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        return redirect()->route('booking.track', ['code' => 'XR-20261014-AB12'])
            ->with('success', "Pemesanan berhasil diproses! Kode Booking Anda: XR-20261014-AB12. Simpan kode ini untuk memantau status pesanan.");
    }

    /**
     * Master dummy bookings with 4 comprehensive statuses for testing.
     */
    public static function getDummyBookings(): array
    {
        return [
            // 1. PENDING: Menunggu Konfirmasi
            [
                'id' => 1,
                'code' => 'XR-20261014-AB12',
                'status' => 'pending',
                'status_label' => 'Menunggu Konfirmasi',
                'status_desc' => 'Admin kami sedang memverifikasi data identitas (KTP & SIM) dan ketersediaan unit di sistem garasi.',
                'status_color' => 'amber', // amber / emerald / blue / gray
                'step_index' => 1, // 1 to 4
                'created_at' => '14 Okt 2026, 14:30 WIB',
                'customer_name' => 'Ahmad Fauzi',
                'customer_phone' => '0812-3456-7890',
                'customer_category' => 'Mahasiswa (Diskon Pelajar)',
                'vehicle' => [
                    'id' => 1,
                    'slug' => 'honda-beat-cbs',
                    'name' => 'Honda Beat CBS eSP',
                    'brand' => 'Honda',
                    'type' => 'motor',
                    'plate' => 'B 4821 KTF',
                    'transmission' => 'Matic CVT',
                    'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                ],
                'rental_period' => [
                    'start_datetime' => '15 Okt 2026 • 09:00 WIB',
                    'end_datetime' => '18 Okt 2026 • 09:00 WIB',
                    'duration_type' => 'daily',
                    'duration_label' => '3 Hari (Harian)',
                    'duration_count' => 3,
                ],
                'pickup' => [
                    'method' => 'garage',
                    'label' => 'Ambil Sendiri di Garasi (Gratis)',
                    'fee' => 0,
                    'address' => 'Garasi Pusat X-RENT, Jl. Cilandak KKO No. 45, Pasar Minggu, Jakarta Selatan (Patokan: 50m samping SPBU Pertamina)',
                    'contact_person' => 'Bpk. Hendra (Koordinator Garasi) • 0812-8888-9999',
                ],
                'payment' => [
                    'unit_rate' => 85000,
                    'subtotal_rent' => 255000,
                    'delivery_fee' => 0,
                    'deposit_amount' => 50000,
                    'total_payable' => 305000,
                    'status' => 'Belum Dibayar',
                    'method' => 'Bayar di Tempat (Tunai / QRIS / Transfer BCA)',
                    'is_paid' => false,
                ],
                'notes' => 'Tolong sediakan helm ukuran L dan jas hujan ponco warna gelap.',
                'timeline' => [
                    [
                        'step' => 1,
                        'name' => 'Pesanan Dibuat',
                        'desc' => 'Formulir online berhasil dikirim ke server X-RENT.',
                        'time' => '14 Okt 2026, 14:30 WIB',
                        'status' => 'completed',
                        'icon' => 'receipt_long',
                    ],
                    [
                        'step' => 2,
                        'name' => 'Verifikasi Identitas & Unit',
                        'desc' => 'Admin mengecek keabsahan KTP & SIM C penyewa.',
                        'time' => 'Sedang berlangsung...',
                        'status' => 'current',
                        'icon' => 'verified_user',
                    ],
                    [
                        'step' => 3,
                        'name' => 'Pengambilan / Serah Terima Unit',
                        'desc' => 'Penyewa datang ke Garasi Cilandak untuk cek fisik unit & kunci.',
                        'time' => 'Est. 15 Okt 2026, 09:00 WIB',
                        'status' => 'upcoming',
                        'icon' => 'key',
                    ],
                    [
                        'step' => 4,
                        'name' => 'Selesai & Pengembalian Deposit',
                        'desc' => 'Unit dikembalikan utuh, deposit Rp 50.000 direfund seketika.',
                        'time' => 'Est. 18 Okt 2026, 09:00 WIB',
                        'status' => 'upcoming',
                        'icon' => 'task_alt',
                    ],
                ],
            ],

            // 2. CONFIRMED: Pesanan Diterima / Siap Diambil
            [
                'id' => 2,
                'code' => 'XR-20261014-CD34',
                'status' => 'confirmed',
                'status_label' => 'Pesanan Dikonfirmasi (Siap Diantar)',
                'status_desc' => 'Unit telah disiapkan, dicuci bersih, dan dijadwalkan untuk pengantaran drop-off ke lokasi Anda.',
                'status_color' => 'blue',
                'step_index' => 2,
                'created_at' => '14 Okt 2026, 10:15 WIB',
                'customer_name' => 'Jessica Stephanie',
                'customer_phone' => '0813-9876-5432',
                'customer_category' => 'Wisatawan Mancanegara / Domestik',
                'vehicle' => [
                    'id' => 8,
                    'slug' => 'honda-brio-rs-matic',
                    'name' => 'Honda Brio RS Urbanite Matic',
                    'brand' => 'Honda',
                    'type' => 'mobil',
                    'plate' => 'B 1928 WQA',
                    'transmission' => 'Matic CVT',
                    'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                ],
                'rental_period' => [
                    'start_datetime' => '15 Okt 2026 • 11:00 WIB',
                    'end_datetime' => '17 Okt 2026 • 11:00 WIB',
                    'duration_type' => 'daily',
                    'duration_label' => '2 Hari (Harian)',
                    'duration_count' => 2,
                ],
                'pickup' => [
                    'method' => 'delivery',
                    'label' => 'Layanan Antar ke Lokasi (Drop-Off)',
                    'fee' => 25000,
                    'address' => 'Hotel Artotel Thamrin, Lobby Utama, Jl. Sunda No. 3, Menteng, Jakarta Pusat',
                    'contact_person' => 'Driver: Mas Dimas (0812-9911-2233) • Toyota HiAce Antar',
                ],
                'payment' => [
                    'unit_rate' => 280000,
                    'subtotal_rent' => 560000,
                    'delivery_fee' => 25000,
                    'deposit_amount' => 200000,
                    'total_payable' => 785000,
                    'status' => 'DP Terbayar 50% (Rp 392.500)',
                    'method' => 'Transfer Bank BCA (Sisa Pelunasan saat Serah Kunci)',
                    'is_paid' => true,
                ],
                'notes' => 'Mohon mobil disemprot disinfektan wangi aroma kopi / citrus.',
                'timeline' => [
                    [
                        'step' => 1,
                        'name' => 'Pesanan Dibuat',
                        'desc' => 'Formulir online berhasil dikirim ke server X-RENT.',
                        'time' => '14 Okt 2026, 10:15 WIB',
                        'status' => 'completed',
                        'icon' => 'receipt_long',
                    ],
                    [
                        'step' => 2,
                        'name' => 'Verifikasi & Konfirmasi Disetujui',
                        'desc' => 'Admin telah menyetujui jadwal sewa & menerima DP komitmen.',
                        'time' => '14 Okt 2026, 10:45 WIB',
                        'status' => 'completed',
                        'icon' => 'verified_user',
                    ],
                    [
                        'step' => 3,
                        'name' => 'Pengantaran Drop-off Unit',
                        'desc' => 'Driver akan tiba di Lobby Hotel Artotel 30 menit sebelum jadwal.',
                        'time' => 'Dijadwalkan: 15 Okt 2026, 10:30 WIB',
                        'status' => 'current',
                        'icon' => 'local_shipping',
                    ],
                    [
                        'step' => 4,
                        'name' => 'Selesai & Pengembalian Deposit',
                        'desc' => 'Unit diambil kembali oleh driver di lobby hotel & refund deposit.',
                        'time' => 'Est. 17 Okt 2026, 11:00 WIB',
                        'status' => 'upcoming',
                        'icon' => 'task_alt',
                    ],
                ],
            ],

            // 3. ONGOING: Sedang Disewa
            [
                'id' => 3,
                'code' => 'XR-20261014-EF56',
                'status' => 'ongoing',
                'status_label' => 'Sedang Disewa (Aktif)',
                'status_desc' => 'Unit kendaraan saat ini sedang aktif digunakan oleh penyewa. Nikmati perjalanan aman bersama X-RENT!',
                'status_color' => 'emerald',
                'step_index' => 3,
                'created_at' => '13 Okt 2026, 08:00 WIB',
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '0817-2233-4455',
                'customer_category' => 'Pekerja Kantor / Perjalanan Dinas',
                'vehicle' => [
                    'id' => 7,
                    'slug' => 'toyota-all-new-avanza',
                    'name' => 'Toyota All New Avanza 1.5 G',
                    'brand' => 'Toyota',
                    'type' => 'mobil',
                    'plate' => 'B 2841 TRL',
                    'transmission' => 'Matic CVT',
                    'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                ],
                'rental_period' => [
                    'start_datetime' => '13 Okt 2026 • 10:00 WIB',
                    'end_datetime' => '16 Okt 2026 • 10:00 WIB',
                    'duration_type' => 'daily',
                    'duration_label' => '3 Hari (Harian)',
                    'duration_count' => 3,
                ],
                'pickup' => [
                    'method' => 'garage',
                    'label' => 'Ambil Sendiri di Garasi Pusat Cilandak',
                    'fee' => 0,
                    'address' => 'Garasi Pusat X-RENT, Jl. Cilandak KKO No. 45, Jakarta Selatan',
                    'contact_person' => 'Koordinator: Mas Bayu (0812-7766-5544)',
                ],
                'payment' => [
                    'unit_rate' => 380000,
                    'subtotal_rent' => 1140000,
                    'delivery_fee' => 0,
                    'deposit_amount' => 200000,
                    'total_payable' => 1340000,
                    'status' => 'Lunas 100% (Termasuk Asuransi All-Risk)',
                    'method' => 'Transfer QRIS Mandiri',
                    'is_paid' => true,
                ],
                'notes' => 'Rute dinas kantor menuju Kawasan Industri Karawang & Bandung.',
                'timeline' => [
                    [
                        'step' => 1,
                        'name' => 'Pesanan Dibuat',
                        'desc' => 'Formulir online berhasil dikirim ke server X-RENT.',
                        'time' => '13 Okt 2026, 08:00 WIB',
                        'status' => 'completed',
                        'icon' => 'receipt_long',
                    ],
                    [
                        'step' => 2,
                        'name' => 'Verifikasi Disetujui',
                        'desc' => 'Dokumen SIM A & KTP telah diverifikasi lengkap.',
                        'time' => '13 Okt 2026, 09:15 WIB',
                        'status' => 'completed',
                        'icon' => 'verified_user',
                    ],
                    [
                        'step' => 3,
                        'name' => 'Serah Terima Kunci Unit',
                        'desc' => 'Unit Avanza B 2841 TRL telah diserahterimakan kepada Budi Santoso.',
                        'time' => '13 Okt 2026, 10:05 WIB',
                        'status' => 'completed',
                        'icon' => 'key',
                    ],
                    [
                        'step' => 4,
                        'name' => 'Pengembalian Unit & Refund Deposit',
                        'desc' => 'Jadwal pengembalian ke Garasi Cilandak sebelum batas sewa berakhir.',
                        'time' => 'Batas Waktu: 16 Okt 2026, 10:00 WIB',
                        'status' => 'current',
                        'icon' => 'task_alt',
                    ],
                ],
            ],

            // 4. COMPLETED: Selesai
            [
                'id' => 4,
                'code' => 'XR-20261014-GH78',
                'status' => 'completed',
                'status_label' => 'Sewa Selesai (Unit Telah Dikembalikan)',
                'status_desc' => 'Unit telah diterima kembali oleh staf garasi dalam kondisi sangat baik. Deposit jaminan telah direfund 100%. Terima kasih telah mempercayai X-RENT!',
                'status_color' => 'blue',
                'step_index' => 4,
                'created_at' => '10 Okt 2026, 09:00 WIB',
                'customer_name' => 'Rian Hidayat',
                'customer_phone' => '0819-7788-9900',
                'customer_category' => 'Umum',
                'vehicle' => [
                    'id' => 4,
                    'slug' => 'yamaha-nmax-155-connected',
                    'name' => 'Yamaha NMAX 155 Connected ABS',
                    'brand' => 'Yamaha',
                    'type' => 'motor',
                    'plate' => 'B 3109 PZK',
                    'transmission' => 'Matic CVT',
                    'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                ],
                'rental_period' => [
                    'start_datetime' => '11 Okt 2026 • 08:00 WIB',
                    'end_datetime' => '14 Okt 2026 • 08:00 WIB',
                    'duration_type' => 'daily',
                    'duration_label' => '3 Hari (Harian)',
                    'duration_count' => 3,
                ],
                'pickup' => [
                    'method' => 'garage',
                    'label' => 'Ambil Sendiri di Garasi Pusat Cilandak',
                    'fee' => 0,
                    'address' => 'Garasi Pusat X-RENT, Jl. Cilandak KKO No. 45, Jakarta Selatan',
                    'contact_person' => 'Staf Penerima: Mas Agung (0812-8888-9999)',
                ],
                'payment' => [
                    'unit_rate' => 145000,
                    'subtotal_rent' => 435000,
                    'delivery_fee' => 0,
                    'deposit_amount' => 50000,
                    'total_payable' => 485000,
                    'status' => 'Selesai & Deposit Direfund Penuh (Rp 50.000)',
                    'method' => 'Tunai di Garasi',
                    'is_paid' => true,
                ],
                'notes' => 'Pengembalian tepat waktu, helm & jas hujan lengkap tanpa cacat.',
                'timeline' => [
                    [
                        'step' => 1,
                        'name' => 'Pesanan Dibuat',
                        'desc' => 'Formulir online berhasil dikirim ke server X-RENT.',
                        'time' => '10 Okt 2026, 09:00 WIB',
                        'status' => 'completed',
                        'icon' => 'receipt_long',
                    ],
                    [
                        'step' => 2,
                        'name' => 'Verifikasi Disetujui',
                        'desc' => 'Data KTP & SIM C disetujui tanpa kendala.',
                        'time' => '10 Okt 2026, 11:00 WIB',
                        'status' => 'completed',
                        'icon' => 'verified_user',
                    ],
                    [
                        'step' => 3,
                        'name' => 'Unit Diambil Penyewa',
                        'desc' => 'Penyewa menerima kunci, 2 helm SNI, dan unit NMAX.',
                        'time' => '11 Okt 2026, 08:10 WIB',
                        'status' => 'completed',
                        'icon' => 'key',
                    ],
                    [
                        'step' => 4,
                        'name' => 'Inspeksi & Refund Deposit Selesai',
                        'desc' => 'Unit diterima kembali dalam kondisi prima. Deposit Rp 50.000 ditransfer kembali.',
                        'time' => '14 Okt 2026, 08:15 WIB',
                        'status' => 'completed',
                        'icon' => 'task_alt',
                    ],
                ],
            ],
        ];
    }
}
