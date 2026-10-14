<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the X-RENT landing page with real data from MySQL (Task 3.2 roadmap.md).
     */
    public function index(): Response
    {
        $cars = Vehicle::with('category')
            ->where('type', 'mobil')
            ->get();

        $motors = Vehicle::with('category')
            ->where('type', 'motor')
            ->get();

        $faqs = [
            [
                'q' => 'Apa saja syarat yang dibutuhkan untuk menyewa motor / mobil di X-RENT?',
                'a' => 'Cukup membawa E-KTP asli yang aktif, SIM (SIM C untuk motor atau SIM A untuk mobil), serta bukti pendukung seperti kartu mahasiswa/ID karyawan atau akun media sosial aktif. Kami tidak menahan dokumen berharga Anda.',
            ],
            [
                'q' => 'Berapa besaran deposit jaminan dan bagaimana proses pengembaliannya?',
                'a' => 'Deposit jaminan ringan: Rp 50.000 untuk motor dan Rp 200.000 untuk mobil. Uang deposit dikembalikan utuh 100% secara langsung via transfer bank atau tunai saat Anda mengembalikan unit dalam keadaan baik.',
            ],
            [
                'q' => 'Apakah sewa motor sudah termasuk helm dan jas hujan?',
                'a' => 'Ya, setiap penyewaan unit motor di X-RENT sudah otomatis dilengkapi 2 buah Helm SNI bersih & higienis, 2 jas hujan tebal, serta holder smartphone anti-air di stang tanpa biaya tambahan.',
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

        $garageLocations = [
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
        ];

        return Inertia::render('Home', [
            'cars' => $cars,
            'motors' => $motors,
            'faqs' => $faqs,
            'hotline' => '(021) 7888-9999 / 0812-8888-9999',
            'garageLocations' => $garageLocations,
        ]);
    }
}
