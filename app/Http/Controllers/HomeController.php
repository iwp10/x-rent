<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the X-RENT landing page with rich dummy data (UI-First).
     */
    public function index(): Response
    {
        $cars = [
            [
                'id' => 1,
                'name' => 'Toyota All New Avanza',
                'brand' => 'Toyota',
                'category' => 'mpv',
                'category_label' => 'MPV Keluarga',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Terpopuler',
                'is_available' => true,
                'status_label' => 'Tersedia Hari Ini',
                'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 350000,
                    'weekly' => 2200000,
                    'monthly' => 7800000,
                ],
                'specs' => [
                    ['icon' => 'airline_seat_recline_normal', 'label' => '7 Kursi'],
                    ['icon' => 'settings', 'label' => 'Automatic CVT'],
                    ['icon' => 'ac_unit', 'label' => 'Double Blower'],
                    ['icon' => 'local_gas_station', 'label' => 'Bensin Irit'],
                ],
                'driver_option' => 'Lepas Kunci & Supir',
                'features' => ['USB Port Tiap Baris', 'Sensor Parkir', 'Audio Bluetooth', 'Asuransi All-Risk'],
            ],
            [
                'id' => 2,
                'name' => 'Innova Zenix Hybrid',
                'brand' => 'Toyota',
                'category' => 'mpv',
                'category_label' => 'Premium Hybrid MPV',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Rekomendasi',
                'is_available' => true,
                'status_label' => 'Hybrid Eco Ready',
                'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 750000,
                    'weekly' => 4700000,
                    'monthly' => 16500000,
                ],
                'specs' => [
                    ['icon' => 'airline_seat_recline_extra', 'label' => 'Captain Seat'],
                    ['icon' => 'wb_sunny', 'label' => 'Panoramic Roof'],
                    ['icon' => 'bolt', 'label' => 'Hybrid Electric'],
                    ['icon' => 'shield', 'label' => 'Toyota Safety'],
                ],
                'driver_option' => 'Lepas Kunci & Supir',
                'features' => ['Wireless Charger', 'Layar 10 Inch Apple CarPlay', 'Cruise Control', 'Dashcam'],
            ],
            [
                'id' => 3,
                'name' => 'Mitsubishi Xpander Ultimate',
                'brand' => 'Mitsubishi',
                'category' => 'mpv',
                'category_label' => 'Crossover MPV',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Best Value',
                'is_available' => true,
                'status_label' => '3 Unit Tersedia',
                'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 400000,
                    'weekly' => 2500000,
                    'monthly' => 8900000,
                ],
                'specs' => [
                    ['icon' => 'group', 'label' => '7 Kursi Luas'],
                    ['icon' => 'key', 'label' => 'Keyless Start'],
                    ['icon' => 'videocam', 'label' => 'Kamera Mundur'],
                    ['icon' => 'height', 'label' => 'Ground 220mm'],
                ],
                'driver_option' => 'Lepas Kunci & Supir',
                'features' => ['Suspensi Sangat Nyaman', 'Electric Parking Brake', 'Kabin Kedap', 'ISOFIX'],
            ],
            [
                'id' => 4,
                'name' => 'Honda Brio RS Matic',
                'brand' => 'Honda',
                'category' => 'city',
                'category_label' => 'Compact Hatchback',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'City Car',
                'is_available' => false,
                'status_label' => 'Sedang Disewa (Ready Besok)',
                'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 280000,
                    'weekly' => 1750000,
                    'monthly' => 6200000,
                ],
                'specs' => [
                    ['icon' => 'person', 'label' => '5 Penumpang'],
                    ['icon' => 'speed', 'label' => 'CVT Sporty'],
                    ['icon' => 'touch_app', 'label' => 'Touchscreen'],
                    ['icon' => 'park', 'label' => 'Mudah Parkir'],
                ],
                'driver_option' => 'Lepas Kunci',
                'features' => ['Konsumsi BBM 1:21 km/L', 'Audio Steering Switch', 'Dual SRS Airbags', 'Fog Lamp LED'],
            ],
        ];

        // Rich dummy data for motorcycles (UI-First)
        $motors = [
            [
                'id' => 101,
                'name' => 'Yamaha NMAX 155 Connected',
                'brand' => 'Yamaha',
                'category' => 'maxi',
                'category_label' => 'Maxi Scooter',
                'type' => 'motor',
                'year' => '2024',
                'badge' => 'Maxi Favorit',
                'is_available' => true,
                'status_label' => 'Tersedia Hari Ini',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 120000,
                    'weekly' => 750000,
                    'monthly' => 2600000,
                ],
                'specs' => [
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI Bersih'],
                    ['icon' => 'umbrella', 'label' => '2 Jas Hujan Ganda'],
                    ['icon' => 'smartphone', 'label' => 'Phone Holder Stang'],
                    ['icon' => 'two_wheeler', 'label' => '155 cc VVA'],
                ],
                'driver_option' => 'Lepas Kunci',
                'features' => ['Y-Connect Bluetooth', 'Sub-tank Suspension', 'Lampu Hazard & LED', 'Power Socket 12V'],
            ],
            [
                'id' => 102,
                'name' => 'Honda PCX 160 ABS',
                'brand' => 'Honda',
                'category' => 'maxi',
                'category_label' => 'Premium Touring',
                'type' => 'motor',
                'year' => '2024',
                'badge' => 'Rem ABS Safe',
                'is_available' => true,
                'status_label' => 'Tersedia Hari Ini',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 125000,
                    'weekly' => 780000,
                    'monthly' => 2700000,
                ],
                'specs' => [
                    ['icon' => 'inventory_2', 'label' => 'Bagasi Luas 30L'],
                    ['icon' => 'security', 'label' => 'Rem ABS Depan'],
                    ['icon' => 'key', 'label' => 'Honda Smart Key'],
                    ['icon' => 'two_wheeler', 'label' => '160 cc eSP+'],
                ],
                'driver_option' => 'Lepas Kunci',
                'features' => ['Honda Selectable Torque Control (HSTC)', 'Port USB Type-C', 'Kapasitas Tangki 8.1 L', '2 Helm Fullface'],
            ],
            [
                'id' => 103,
                'name' => 'Vespa Primavera 150 i-Get',
                'brand' => 'Vespa',
                'category' => 'retro',
                'category_label' => 'Lifestyle Retro',
                'type' => 'motor',
                'year' => '2023',
                'badge' => 'Lifestyle Retro',
                'is_available' => true,
                'status_label' => '2 Unit Tersedia',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 250000,
                    'weekly' => 1550000,
                    'monthly' => 5400000,
                ],
                'specs' => [
                    ['icon' => 'palette', 'label' => 'Warna Pastel Estetik'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm Bogo Kulit'],
                    ['icon' => 'power', 'label' => 'Mesin i-Get 3V'],
                    ['icon' => 'two_wheeler', 'label' => '150 cc Injeksi'],
                ],
                'driver_option' => 'Lepas Kunci',
                'features' => ['Velg Alloy 12 Inch', 'Rem Depan ABS', 'Immobilizer Anti Maling', 'Sangat Cocok Untuk Foto & Konten'],
            ],
            [
                'id' => 104,
                'name' => 'Honda Vario 160 eSP+',
                'brand' => 'Honda',
                'category' => 'matic',
                'category_label' => 'Sporty Matic',
                'type' => 'motor',
                'year' => '2024',
                'badge' => 'Paling Irit',
                'is_available' => false,
                'status_label' => 'Sedang Disewa (Ready Sore)',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 100000,
                    'weekly' => 620000,
                    'monthly' => 2200000,
                ],
                'specs' => [
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI Bersih'],
                    ['icon' => 'bolt', 'label' => 'Mesin 160 cc 4-Katup'],
                    ['icon' => 'key', 'label' => 'Smart Keyless'],
                    ['icon' => 'local_gas_station', 'label' => 'BBM 1:47 km/L'],
                ],
                'driver_option' => 'Lepas Kunci',
                'features' => ['Rangka eSAF Bergaransi', 'Ban Tubeless Lebar', 'USB Charger', 'Bagasi 18 Liter'],
            ],
        ];

        $faqs = [
            [
                'q' => 'Apa saja persyaratan untuk sewa mobil lepas kunci?',
                'a' => 'Untuk sewa mobil lepas kunci, Anda cukup menyiapkan: 1) E-KTP asli yang masih berlaku, 2) SIM A aktif, 3) Akun media sosial aktif (Instagram/LinkedIn) atau ID Karyawan/Tiket Perjalanan, dan 4) Deposit jaminan yang akan dikembalikan utuh 100% setelah masa sewa berakhir dan unit dikembalikan dalam keadaan baik.',
            ],
            [
                'q' => 'Apa syarat sewa motor di X-RENT?',
                'a' => 'Sewa motor sangat praktis: Cukup tunjukkan E-KTP asli dan SIM C aktif. Kami menyertakan 2 helm berstandar SNI yang wangi dan bersih, 2 jas hujan berkualitas, serta holder smartphone anti-air secara gratis di setiap unit motor.',
            ],
            [
                'q' => 'Apakah harga sewa sudah termasuk asuransi dan pajak?',
                'a' => 'Ya, seluruh harga yang tertera di X-RENT sudah mencakup perlindungan asuransi komprehensif (All-Risk) dan pajak resmi. Tidak ada biaya tersembunyi yang akan ditagihkan saat serah terima.',
            ],
            [
                'q' => 'Bagaimana jika saya membutuhkan unit diantar ke Bandara atau Hotel?',
                'a' => 'Kami menyediakan layanan antar-jemput unit ke Bandara Soekarno-Hatta (Terminal 2 & 3), Bandara Halim Perdanakusuma, stasiun kereta api, hotel, maupun alamat rumah Anda di seluruh area Jabodetabek dengan konfirmasi terlebih dahulu.',
            ],
            [
                'q' => 'Berapa lama proses verifikasi pemesanan?',
                'a' => 'Tim verifikasi kami beroperasi secara cepat. Rata-rata verifikasi data pemesan hanya memerlukan waktu 10 hingga 15 menit melalui WhatsApp.',
            ],
        ];

        return Inertia::render('Home', [
            'cars' => $cars,
            'motors' => $motors,
            'faqs' => $faqs,
            'hotline' => '(021) 7888-9999 / 0812-8888-9999',
            'garageLocations' => [
                [
                    'id' => 'cilandak',
                    'name' => 'Garasi Pusat Cilandak (Jakarta Selatan)',
                    'badge' => 'Kantor Pusat & Workshop Utama',
                    'address' => 'Jl. Raya Otomotif No. 88, Cilandak Barat, Jakarta Selatan 12430',
                    'hours' => 'Buka Setiap Hari (06.00 - 23.00 WIB)',
                    'phone' => '0812-8888-9999',
                    'notes' => 'Dekat Tol TB Simatupang & Stasiun MRT Fatmawati. Fasilitas customer lounge ber-AC, kopi gratis, dan parkir inap aman.',
                    'features' => ['Pick-up & Drop-off 24 Jam', 'Customer Lounge AC', 'Titip Kendaraan Pribadi', 'Inspeksi & Cuci Unit'],
                ],
                [
                    'id' => 'soetta',
                    'name' => 'Hub Bandara Soekarno-Hatta (T2 & T3)',
                    'badge' => 'Shuttle On-Time Bandara',
                    'address' => 'Area Perimeter Selatan & Pool Shuttle T2/T3, Bandara Soekarno-Hatta',
                    'hours' => 'Layanan Siaga 24 Jam Nonstop',
                    'phone' => '0812-8888-9999',
                    'notes' => 'Serah terima langsung di pintu kedatangan domestik/internasional. Bebas repot, unit siap jalan begitu pesawat mendarat.',
                    'features' => ['Meet & Greet Pintu Kedatangan', 'Gratis Antar Terminal', 'Emergency Backup Unit', 'Pelayanan 24 Jam'],
                ],
                [
                    'id' => 'bsd',
                    'name' => 'Hub Cabang BSD City (Tangerang Selatan)',
                    'badge' => 'Wilayah Barat Jabodetabek',
                    'address' => 'Ruko Golden Boulevard Blok S No. 12, BSD City, Tangerang Selatan',
                    'hours' => 'Buka Setiap Hari (07.00 - 22.00 WIB)',
                    'phone' => '0812-8888-9999',
                    'notes' => 'Melayani pengantaran cepat ke area Serpong, Karawaci, Gading Serpong, Alam Sutera, dan Bintaro.',
                    'features' => ['Layanan Antar Hotel/Apartemen', 'Parkir Luas', 'Akses Tol Serpong-Balaraja'],
                ],
                [
                    'id' => 'gambir',
                    'name' => 'Titik Temu Stasiun Gambir & Senen',
                    'badge' => 'Titik Transit Kereta Cepat & Eksekutif',
                    'address' => 'Area Drop-off Pintu Utara Stasiun Gambir & Stasiun Pasar Senen',
                    'hours' => 'Sesuai Jadwal Kedatangan Kereta Api',
                    'phone' => '0812-8888-9999',
                    'notes' => 'Petugas kami standby di area kedatangan untuk langsung menyerahkan kunci dan berkas kendaraan.',
                    'features' => ['On-Time Sesuai Jadwal KA', 'Kordinasi WhatsApp Cepat', 'Serah Terima Praktis'],
                ],
            ],
        ]);
    }
}
