<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    /**
     * Display the fleet catalog page (stitch/screen-catalog.html reference).
     */
    public function index(Request $request): Response
    {
        $vehicles = self::getFleetData();
        $initialCategory = $request->query('kategori', 'all');

        return Inertia::render('Catalog', [
            'vehicles' => $vehicles,
            'initialCategory' => $initialCategory,
            'stats' => [
                'total_units' => '48+ Unit',
                'service_guarantee' => '100% Prima',
                'insurance' => 'All-Risk',
            ],
            'categories' => [
                ['id' => 'all', 'label' => 'Semua Unit (8)'],
                ['id' => 'matic-ekonomis', 'label' => 'Matic Ekonomis (110-125cc)'],
                ['id' => 'matic-maxi', 'label' => 'Maxi Scooter (150-160cc)'],
                ['id' => 'mobil-keluarga', 'label' => 'Mobil & MPV Keluarga'],
                ['id' => 'hybrid-listrik', 'label' => 'Hybrid & Elektrik'],
            ],
        ]);
    }

    /**
     * Direct filter for Cars.
     */
    public function cars(): Response
    {
        return $this->index(new Request(['kategori' => 'mobil-keluarga']));
    }

    /**
     * Direct filter for Motorcycles.
     */
    public function motors(): Response
    {
        return $this->index(new Request(['kategori' => 'matic-maxi']));
    }

    /**
     * Master dataset for all vehicles.
     */
    public static function getFleetData(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Honda PCX 160 ABS',
                'brand' => 'Honda',
                'category' => 'matic-maxi',
                'category_label' => 'Maxi Scooter 160cc',
                'type' => 'motor',
                'year' => '2024',
                'badge' => 'Maxi Scooter 160cc',
                'subtitle' => 'Keyless Smart Key • Rem ABS Depan & Belakang',
                'is_available' => true,
                'status_label' => 'Tersedia',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 140000,
                    'weekly' => 880000,
                    'monthly' => 2950000,
                ],
                'specs' => [
                    ['icon' => 'speed', 'label' => '160 cc eSP+'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'local_gas_station', 'label' => '1:45 km/L'],
                ],
                'facilities' => 'Holder HP anti-getar, 2 jas hujan ponco, gembok cakram, ban tubeless baru.',
                'driver_option' => 'Lepas Kunci',
                'features' => ['Rem ABS Depan', 'HSTC Traction Control', 'USB Port Charger', 'Smart Keyless'],
            ],
            [
                'id' => 2,
                'name' => 'Yamaha NMAX 155 Connected',
                'brand' => 'Yamaha',
                'category' => 'matic-maxi',
                'category_label' => 'Maxi Scooter 155cc',
                'type' => 'motor',
                'year' => '2023',
                'badge' => 'Maxi Scooter 155cc',
                'subtitle' => 'Traction Control System (TCS) • Y-Connect Tech',
                'is_available' => true,
                'status_label' => 'Tersedia',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 135000,
                    'weekly' => 850000,
                    'monthly' => 2800000,
                ],
                'specs' => [
                    ['icon' => 'speed', 'label' => '155 cc VVA'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'luggage', 'label' => 'Bagasi 24L'],
                ],
                'facilities' => 'Charger USB aktif, holder stang kokoh, 2 jas hujan, servis berkala Yamaha Flagship.',
                'driver_option' => 'Lepas Kunci',
                'features' => ['Y-Connect Bluetooth', 'Sub-tank Suspension', 'Lampu LED & Hazard', 'Power Socket 12V'],
            ],
            [
                'id' => 3,
                'name' => 'Honda Vario 125 eSP',
                'brand' => 'Honda',
                'category' => 'matic-ekonomis',
                'category_label' => 'Matic Irit 125cc',
                'type' => 'motor',
                'year' => '2024',
                'badge' => 'Matic Irit 125cc',
                'subtitle' => 'Idling Stop System • Paling Irit BBM Perkotaan',
                'is_available' => true,
                'status_label' => 'Tersedia',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 95000,
                    'weekly' => 595000,
                    'monthly' => 1950000,
                ],
                'specs' => [
                    ['icon' => 'speed', 'label' => '125 cc PGM-FI'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'local_gas_station', 'label' => '1:52 km/L'],
                ],
                'facilities' => 'Sangat lincah selap-selip macet ibukota, konsumsi BBM ultra efisien, 2 jas hujan.',
                'driver_option' => 'Lepas Kunci',
                'features' => ['Idling Stop System', 'Combi Brake System (CBS)', 'Lampu Full LED', 'Bagasi 18 Liter'],
            ],
            [
                'id' => 4,
                'name' => 'Vespa Primavera 150 i-Get',
                'brand' => 'Vespa',
                'category' => 'matic-maxi',
                'category_label' => 'Lifestyle Scooter',
                'type' => 'motor',
                'year' => '2023',
                'badge' => 'Lifestyle Scooter',
                'subtitle' => 'Classic Iconic Design • Mesin Halus i-Get ABS',
                'is_available' => false,
                'status_label' => 'Sedang Disewa (Ready 3 Mei)',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 220000,
                    'weekly' => 1380000,
                    'monthly' => 4500000,
                ],
                'specs' => [
                    ['icon' => 'speed', 'label' => '154.8 cc i-Get'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm Bogo'],
                    ['icon' => 'shield', 'label' => 'Style Estetik'],
                ],
                'facilities' => 'Unit favorit photoshoot & liburan akhir pekan. Booking minimal H-2 untuk kepastian ketersediaan.',
                'driver_option' => 'Lepas Kunci',
                'features' => ['Velg Alloy 12 Inch', 'Rem Depan ABS', 'Immobilizer Anti Maling', 'Warna Pastel Elegan'],
            ],
            [
                'id' => 5,
                'name' => 'Toyota All New Avanza 1.5 G',
                'brand' => 'Toyota',
                'category' => 'mobil-keluarga',
                'category_label' => 'Family MPV 7-Seat',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Family MPV 7-Seat',
                'subtitle' => 'Transmisi CVT Halus • Long Sofa Mode Kabin',
                'is_available' => true,
                'status_label' => 'Tersedia',
                'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 380000,
                    'weekly' => 2390000,
                    'monthly' => 7800000,
                ],
                'specs' => [
                    ['icon' => 'group', 'label' => '7 Penumpang'],
                    ['icon' => 'settings', 'label' => 'CVT Otomatis'],
                    ['icon' => 'luggage', 'label' => '4 Koper Besar'],
                ],
                'facilities' => 'Tersedia opsi Lepas Kunci (SIM A) atau All-In Supir ramah & berpengalaman luar kota.',
                'driver_option' => 'Lepas Kunci & Supir',
                'features' => ['AC Double Blower', 'Sensor Parkir & Kamera', 'USB Port Tiap Baris', 'Asuransi Garda Oto'],
            ],
            [
                'id' => 6,
                'name' => 'Toyota Innova Zenix Hybrid',
                'brand' => 'Toyota',
                'category' => 'hybrid-listrik',
                'category_label' => 'Hybrid Luxury MPV',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Hybrid Luxury MPV',
                'subtitle' => 'Captain Seat Ottoman • Sunroof • EV Quiet Mode',
                'is_available' => true,
                'status_label' => 'Tersedia',
                'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 750000,
                    'weekly' => 4700000,
                    'monthly' => 16500000,
                ],
                'specs' => [
                    ['icon' => 'electric_car', 'label' => '2.0L Hybrid'],
                    ['icon' => 'airline_seat_recline_extra', 'label' => 'Captain Seat'],
                    ['icon' => 'wb_sunny', 'label' => 'Panoramic Roof'],
                ],
                'facilities' => 'Perjalanan bisnis & VIP sangat prestisius. Konsumsi bensin seirit city car dengan kabin super senyap.',
                'driver_option' => 'Lepas Kunci & Supir',
                'features' => ['Layar 10 Inch Apple CarPlay', 'Wireless Charging', 'Toyota Safety Sense', 'Dashcam Depan-Belakang'],
            ],
            [
                'id' => 7,
                'name' => 'Mitsubishi Xpander Ultimate',
                'brand' => 'Mitsubishi',
                'category' => 'mobil-keluarga',
                'category_label' => 'Crossover MPV',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Crossover MPV',
                'subtitle' => 'Suspensi Nyaman • Ground Clearance 220mm',
                'is_available' => true,
                'status_label' => 'Tersedia',
                'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                'prices' => [
                    'daily' => 400000,
                    'weekly' => 2500000,
                    'monthly' => 8900000,
                ],
                'specs' => [
                    ['icon' => 'group', 'label' => '7 Kursi Luas'],
                    ['icon' => 'key', 'label' => 'Keyless Start'],
                    ['icon' => 'height', 'label' => 'Ground 220mm'],
                ],
                'facilities' => 'Kabin sangat kedap dan kedap getaran, audio jernih, AC digital dingin maksimal.',
                'driver_option' => 'Lepas Kunci & Supir',
                'features' => ['Electric Parking Brake (EPB)', 'Brake Auto Hold', 'Suspensi Shock Khusus', 'Kamera Mundur HD'],
            ],
            [
                'id' => 8,
                'name' => 'Honda Brio RS Matic',
                'brand' => 'Honda',
                'category' => 'mobil-keluarga',
                'category_label' => 'Compact Hatchback',
                'type' => 'car',
                'year' => '2024',
                'badge' => 'Compact Hatchback',
                'subtitle' => 'Lincah Parkir • Irit BBM 1:21 km/L',
                'is_available' => true,
                'status_label' => 'Tersedia',
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
                ],
                'facilities' => 'Mudah parkir di gang dan area mall sempit, interior sporty dengan audio bluetooth.',
                'driver_option' => 'Lepas Kunci',
                'features' => ['Layar Sentuh 7-Inch', 'Keyless Entry & Push Start', 'Dual SRS Airbags', 'Foglamp LED'],
            ],
        ];
    }
}
