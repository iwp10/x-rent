<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (Task 3.1 & 3.2 roadmap.md).
     */
    public function run(): void
    {
        // 1. Admin Account (Task 3.1)
        User::updateOrCreate(
            ['email' => 'admin@xrent.id'],
            [
                'name' => 'Admin X-RENT',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Categories (Motor & Mobil)
        $categories = [
            'matic-ekonomis' => Category::updateOrCreate(
                ['slug' => 'matic-ekonomis'],
                [
                    'name' => 'Matic Ekonomis',
                    'description' => 'Motor matic gesit & super irit BBM (110-125cc), pilihan utama perjalanan harian perkotaan.',
                ]
            ),
            'matic-maxi' => Category::updateOrCreate(
                ['slug' => 'matic-maxi'],
                [
                    'name' => 'Matic Maxi',
                    'description' => 'Skutik premium 150-160cc dengan bagasi luas, suspensi empuk, dan posisi berkendara ergonomis.',
                ]
            ),
            'sport' => Category::updateOrCreate(
                ['slug' => 'sport'],
                [
                    'name' => 'Sport',
                    'description' => 'Motor kopling manual & retro classic bertorsi padat untuk sensasi berkendara, touring, dan photoshoot.',
                ]
            ),
            'mobil-keluarga' => Category::updateOrCreate(
                ['slug' => 'mobil-keluarga'],
                [
                    'name' => 'MPV & Mobil Keluarga',
                    'description' => 'Mobil keluarga 7-seater dan city car hatchback kabin luas, sejuk, dan hemat bahan bakar.',
                ]
            ),
            'hybrid-listrik' => Category::updateOrCreate(
                ['slug' => 'hybrid-listrik'],
                [
                    'name' => 'Hybrid & Elektrik',
                    'description' => 'Mobil teknologi canggih ramah lingkungan, kabin super senyap, dan efisiensi konsumsi bensin tinggi.',
                ]
            ),
        ];

        // 3. Vehicles (6 Motor X-RENT + 5 Mobil)
        $vehiclesData = [
            // 1. Honda Beat CBS
            [
                'category_id' => $categories['matic-ekonomis']->id,
                'name' => 'Honda Beat CBS eSP',
                'slug' => 'honda-beat-cbs',
                'license_plate' => 'B 4821 KTF',
                'engine_cc' => 110,
                'transmission' => 'Matic CVT',
                'helm_included' => 2,
                'type' => 'motor',
                'brand' => 'Honda',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Paling Irit',
                'subtitle' => 'Paling Gesit & Irit BBM 1:60 km/L untuk Rute Padat',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 85000,
                'price_weekly' => 530000,
                'price_monthly' => 1750000,
                'specs' => [
                    ['icon' => 'speed', 'label' => '110 cc eSP'],
                    ['icon' => 'settings', 'label' => 'Otomatis CVT'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'local_gas_station', 'label' => '1:60 km/L'],
                ],
                'facilities' => 'Gratis 2 helm SNI higienis & wangi, 2 jas hujan ponco tebal, holder smartphone anti-air di stang.',
                'condition_notes' => 'Unit tahun 2024 kilometer rendah (< 12.000 km), ban tubeless baru, servis berkala rutin dealer resmi Astra Honda, kampas rem tebal, dan mesin sangat halus.',
                'features' => ['Combi Brake System (CBS)', 'Power Charger USB', 'Rangka Bergaransi', 'Ban Tubeless Baru'],
                'requirements' => [
                    'E-KTP Asli penyewa (wajib aktif)',
                    'SIM C Aktif yang sah dari Kepolisian',
                    'Deposit jaminan Rp 50.000 (Refundable 100% saat unit dikembalikan aman)',
                    'Denda keterlambatan Rp 15.000 / jam (toleransi 60 menit)',
                ],
            ],

            // 2. Honda Scoopy Prestige
            [
                'category_id' => $categories['matic-ekonomis']->id,
                'name' => 'Honda Scoopy Prestige Smart Key',
                'slug' => 'honda-scoopy-prestige',
                'license_plate' => 'B 3912 SXC',
                'engine_cc' => 110,
                'transmission' => 'Matic CVT',
                'helm_included' => 2,
                'type' => 'motor',
                'brand' => 'Honda',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Desain Retro',
                'subtitle' => 'Smart Key Keyless System • Retro Stylish Bergaya',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 95000,
                'price_weekly' => 590000,
                'price_monthly' => 1950000,
                'specs' => [
                    ['icon' => 'speed', 'label' => '110 cc eSP'],
                    ['icon' => 'key', 'label' => 'Smart Keyless'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm Bogo'],
                    ['icon' => 'usb', 'label' => 'USB Charger'],
                ],
                'facilities' => '2 Helm retro Bogo estetik, 2 jas hujan axio, holder hp stang, dan kunci smart keyless anti-maling.',
                'condition_notes' => 'Cat bodi mulus tanpa baret kasar, jok empuk bersih, remote smart key responsif, ban tebal 95%.',
                'features' => ['Smart Key Keyless', 'Alarm Anti-Theft', 'Lampu Depan LED Projector', 'Bagasi 15.4 Liter'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM C Aktif',
                    'Deposit jaminan Rp 50.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 15.000 / jam',
                ],
            ],

            // 3. Honda Vario 160 ABS
            [
                'category_id' => $categories['matic-maxi']->id,
                'name' => 'Honda Vario 160 eSP+ ABS',
                'slug' => 'honda-vario-160-abs',
                'license_plate' => 'B 5029 ZMA',
                'engine_cc' => 160,
                'transmission' => 'Matic CVT',
                'helm_included' => 2,
                'type' => 'motor',
                'brand' => 'Honda',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Performa 160cc',
                'subtitle' => 'Mesin 4 Katup Bertenaga • Rem ABS Depan Pakem',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 125000,
                'price_weekly' => 780000,
                'price_monthly' => 2550000,
                'specs' => [
                    ['icon' => 'speed', 'label' => '160 cc 4-Katup'],
                    ['icon' => 'shield', 'label' => 'Rem ABS Depan'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'inventory_2', 'label' => 'Bagasi 18 Liter'],
                ],
                'facilities' => '2 Helm fullface/halfface SNI, jas hujan ganda, holder ponsel waterproof, charger USB.',
                'condition_notes' => 'Mesin 160cc 4 katup sangat bertenaga di tanjakan, rem ABS berfungsi normal, ban tubeless tapak lebar prima.',
                'features' => ['Anti-lock Braking System (ABS)', 'Rangka eSAF Bergaransi', 'Full Digital Speedometer', 'Rem Cakram Belakang'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM C Aktif',
                    'Deposit jaminan Rp 50.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 20.000 / jam',
                ],
            ],

            // 4. Yamaha NMAX 155 Connected
            [
                'category_id' => $categories['matic-maxi']->id,
                'name' => 'Yamaha NMAX 155 Connected ABS',
                'slug' => 'yamaha-nmax-155-connected',
                'license_plate' => 'B 3109 PZK',
                'engine_cc' => 155,
                'transmission' => 'Matic CVT',
                'helm_included' => 2,
                'type' => 'motor',
                'brand' => 'Yamaha',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Favorit Touring',
                'subtitle' => 'Posisi Santai Rileks • Bagasi 24L Helm-in Luas',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 145000,
                'price_weekly' => 900000,
                'price_monthly' => 2950000,
                'specs' => [
                    ['icon' => 'speed', 'label' => '155 cc VVA BlueCore'],
                    ['icon' => 'airline_seat_recline_extra', 'label' => 'Sub-Tank Shock'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'inventory_2', 'label' => 'Bagasi 24 Liter'],
                ],
                'facilities' => '2 Helm SNI higienis, 2 jas hujan tebal, phone holder aluminium kokoh, dan soket power charger 12V.',
                'condition_notes' => 'Suspensi tabung belakang empuk, teknologi katup VVA aktif sempurna, ban Pirelli tebal, servis rutin bengkel resmi Yamaha.',
                'features' => ['Traction Control System (TCS)', 'Dual Channel ABS', 'Sub-Tank Rear Suspension', 'Y-Connect Bluetooth'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM C Aktif',
                    'Deposit jaminan Rp 50.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 20.000 / jam',
                ],
            ],

            // 5. Honda PCX 160 RoadSync
            [
                'category_id' => $categories['matic-maxi']->id,
                'name' => 'Honda PCX 160 RoadSync ABS',
                'slug' => 'honda-pcx-160-abs',
                'license_plate' => 'B 4182 QKL',
                'engine_cc' => 160,
                'transmission' => 'Matic CVT',
                'helm_included' => 2,
                'type' => 'motor',
                'brand' => 'Honda',
                'year' => '2024',
                'status' => 'rented',
                'badge' => 'Skutik Mewah',
                'subtitle' => 'Kenyamanan Kelas Atas • Bagasi 30L Super Lapang',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 155000,
                'price_weekly' => 970000,
                'price_monthly' => 3150000,
                'specs' => [
                    ['icon' => 'speed', 'label' => '160 cc eSP+ 4V'],
                    ['icon' => 'shield', 'label' => 'HSTC & ABS'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm SNI'],
                    ['icon' => 'inventory_2', 'label' => 'Bagasi 30.4 Liter'],
                ],
                'facilities' => '2 Helm SNI premium, jas hujan ganda tebal, holder ponsel kokoh, remote smart keyless.',
                'condition_notes' => 'Unit saat ini sedang disewa oleh pelanggan dan siap kembali tersedia untuk reservasi tanggal berikutnya.',
                'features' => ['Honda Selectable Torque Control (HSTC)', 'Smart Key System', 'All LED Lighting', 'USB Type-A Charger'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM C Aktif',
                    'Deposit jaminan Rp 50.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 20.000 / jam',
                ],
            ],

            // 6. Kawasaki W175 SE Cafe (Sport)
            [
                'category_id' => $categories['sport']->id,
                'name' => 'Kawasaki W175 SE Cafe Retro',
                'slug' => 'kawasaki-w175-retro',
                'license_plate' => 'B 6940 HGT',
                'engine_cc' => 177,
                'transmission' => 'Manual 5-Speed',
                'helm_included' => 2,
                'type' => 'motor',
                'brand' => 'Kawasaki',
                'year' => '2023',
                'status' => 'available',
                'badge' => 'Classic Sport',
                'subtitle' => 'Kopling Manual • Sensasi Riding Autentik Klasik',
                'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 150000,
                'price_weekly' => 950000,
                'price_monthly' => 3100000,
                'specs' => [
                    ['icon' => 'speed', 'label' => '177 cc Karburator'],
                    ['icon' => 'settings', 'label' => '5-Speed Manual'],
                    ['icon' => 'sports_motorsports', 'label' => '2 Helm Bogo'],
                    ['icon' => 'shield', 'label' => 'Tangki 14L'],
                ],
                'facilities' => 'Cocok untuk touring akhir pekan & photoshoot. Termasuk 2 helm bogo kulit premium, jas hujan, dan sarung tangan touring.',
                'condition_notes' => 'Mesin 177cc bertorsi padat, kopling enteng tidak pegal di kemacetan, suara knalpot bass adem khas retro.',
                'features' => ['Kopling Manual Responsif', 'Knalpot Peashooter Bass', 'Velg Jari-Jari Hitam', 'Tangki Tear Drop'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM C Aktif',
                    'Deposit jaminan Rp 150.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 25.000 / jam',
                ],
            ],

            // 7. Toyota All New Avanza 1.5 G (Mobil)
            [
                'category_id' => $categories['mobil-keluarga']->id,
                'name' => 'Toyota All New Avanza 1.5 G',
                'slug' => 'toyota-all-new-avanza',
                'license_plate' => 'B 2841 TRL',
                'engine_cc' => 1500,
                'transmission' => 'Matic (CVT)',
                'helm_included' => 0,
                'type' => 'mobil',
                'brand' => 'Toyota',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Terpopuler',
                'subtitle' => 'Transmisi CVT Halus • Long Sofa Mode Kabin Nyaman',
                'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 380000,
                'price_weekly' => 2390000,
                'price_monthly' => 7800000,
                'specs' => [
                    ['icon' => 'group', 'label' => '7 Penumpang'],
                    ['icon' => 'settings', 'label' => 'CVT Otomatis'],
                    ['icon' => 'ac_unit', 'label' => 'Double Blower'],
                    ['icon' => 'luggage', 'label' => '4 Koper Besar'],
                ],
                'facilities' => 'Tersedia opsi Lepas Kunci (SIM A) atau All-In Supir ramah & berpengalaman luar kota.',
                'condition_notes' => 'Kabin bersih wangi non-smoking, AC double blower dingin menggigit, ban tebal 90%, audio bluetooth jernih.',
                'features' => ['AC Double Blower', 'Sensor Parkir & Kamera', 'USB Port Tiap Baris', 'Asuransi Garda Oto'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM A Aktif',
                    'Akun media sosial aktif / ID Karyawan',
                    'Deposit jaminan Rp 200.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 35.000 / jam',
                ],
            ],

            // 8. Honda Brio RS Matic
            [
                'category_id' => $categories['mobil-keluarga']->id,
                'name' => 'Honda Brio RS Urbanite Matic',
                'slug' => 'honda-brio-rs-matic',
                'license_plate' => 'B 1928 WQA',
                'engine_cc' => 1200,
                'transmission' => 'Matic CVT',
                'helm_included' => 0,
                'type' => 'mobil',
                'brand' => 'Honda',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'City Car Lincah',
                'subtitle' => 'Lincah Parkir & Sangat Hemat BBM 1:21 km/L',
                'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 280000,
                'price_weekly' => 1750000,
                'price_monthly' => 6200000,
                'specs' => [
                    ['icon' => 'person', 'label' => '5 Penumpang'],
                    ['icon' => 'settings', 'label' => 'Matic CVT'],
                    ['icon' => 'speed', 'label' => '1.2L i-VTEC'],
                    ['icon' => 'touch_app', 'label' => 'Touchscreen'],
                ],
                'facilities' => 'Mudah parkir di mall sempit, interior sporty dengan audio bluetooth & kabel charger Type-C/Lightning.',
                'condition_notes' => 'Mobil compact paling favorit untuk keliling Jakarta. AC sejuk, suspensi empuk, dan mesin terawat bengkel resmi Honda.',
                'features' => ['Layar Sentuh 7-Inch', 'Keyless Entry & Push Start', 'Dual SRS Airbags', 'Foglamp LED'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM A Aktif',
                    'Deposit jaminan Rp 200.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 30.000 / jam',
                ],
            ],

            // 9. Toyota Kijang Innova Reborn 2.4 Diesel
            [
                'category_id' => $categories['mobil-keluarga']->id,
                'name' => 'Toyota Kijang Innova Reborn 2.4 V Diesel',
                'slug' => 'toyota-innova-reborn-diesel',
                'license_plate' => 'B 7711 KDA',
                'engine_cc' => 2400,
                'transmission' => 'Manual 5-Speed',
                'helm_included' => 0,
                'type' => 'mobil',
                'brand' => 'Toyota',
                'year' => '2023',
                'status' => 'rented',
                'badge' => 'Diesel Badak',
                'subtitle' => 'Mesin 2GD-FTV Bertenaga Luar Biasa untuk Luar Kota & Tanjakan',
                'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 500000,
                'price_weekly' => 3100000,
                'price_monthly' => 10500000,
                'specs' => [
                    ['icon' => 'group', 'label' => '7-8 Penumpang'],
                    ['icon' => 'settings', 'label' => 'Manual 5-Speed'],
                    ['icon' => 'bolt', 'label' => '2.4L Turbo Diesel'],
                    ['icon' => 'luggage', 'label' => '5 Koper Besar'],
                ],
                'facilities' => 'Pilihan utama perjalanan dinas luar kota, tanjakan curam, dan rombongan keluarga besar. Termasuk asuransi All-Risk.',
                'condition_notes' => 'Mesin diesel 2.4L sangat bertenaga dan sehat walafiat, kampas kopling baru, rem tebal, AC triple blower dingin merata.',
                'features' => ['Eco & Power Driving Mode', 'AC Triple Blower Dingin', 'Chassis Ladder Frame Kokoh', 'Sensor Parkir'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM A Aktif',
                    'Deposit jaminan Rp 250.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 40.000 / jam',
                ],
            ],

            // 10. Toyota Innova Zenix Hybrid
            [
                'category_id' => $categories['hybrid-listrik']->id,
                'name' => 'Toyota Innova Zenix Hybrid Q Modellista',
                'slug' => 'toyota-innova-zenix-hybrid',
                'license_plate' => 'B 1001 ZNX',
                'engine_cc' => 2000,
                'transmission' => 'Matic (e-CVT)',
                'helm_included' => 0,
                'type' => 'mobil',
                'brand' => 'Toyota',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Hybrid Luxury',
                'subtitle' => 'Captain Seat Ottoman • Sunroof • EV Quiet Mode',
                'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 750000,
                'price_weekly' => 4700000,
                'price_monthly' => 16500000,
                'specs' => [
                    ['icon' => 'electric_car', 'label' => '2.0L Hybrid'],
                    ['icon' => 'airline_seat_recline_extra', 'label' => 'Captain Seat'],
                    ['icon' => 'settings', 'label' => 'e-CVT Otomatis'],
                    ['icon' => 'wb_sunny', 'label' => 'Panoramic Roof'],
                ],
                'facilities' => 'Perjalanan bisnis & VIP sangat prestisius. Konsumsi bensin seirit city car dengan kabin super senyap. Tersedia wireless charging.',
                'condition_notes' => 'Kondisi 100% mulus seperti baru keluar dari showroom, fitur Toyota Safety Sense aktif, baterai hybrid bergaransi resmi.',
                'features' => ['Layar 10 Inch Apple CarPlay', 'Wireless Charging', 'Toyota Safety Sense', 'Dashcam Depan-Belakang'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM A Aktif',
                    'Deposit jaminan Rp 300.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 50.000 / jam',
                ],
            ],

            // 11. Mitsubishi Xpander Ultimate
            [
                'category_id' => $categories['mobil-keluarga']->id,
                'name' => 'Mitsubishi Xpander Ultimate CVT',
                'slug' => 'mitsubishi-xpander-ultimate',
                'license_plate' => 'B 9238 XPD',
                'engine_cc' => 1500,
                'transmission' => 'Matic (CVT)',
                'helm_included' => 0,
                'type' => 'mobil',
                'brand' => 'Mitsubishi',
                'year' => '2024',
                'status' => 'available',
                'badge' => 'Suspensi Terbaik',
                'subtitle' => 'Ground Clearance Tinggi • Kekedapan Suara Terbaik',
                'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
                ],
                'price_daily' => 370000,
                'price_weekly' => 2350000,
                'price_monthly' => 7600000,
                'specs' => [
                    ['icon' => 'group', 'label' => '7 Kursi Lapang'],
                    ['icon' => 'settings', 'label' => 'Matic CVT'],
                    ['icon' => 'terrain', 'label' => 'Ground Clearance 220mm'],
                    ['icon' => 'volume_off', 'label' => 'Kabin Senyap'],
                ],
                'facilities' => 'Opsi Lepas Kunci atau Supir, charger port tiap baris, cruise control, dan audio surround 6-speaker.',
                'condition_notes' => 'Suspensi kelas SUV empuk di jalan berlubang, AC dingin, ban Bridgestone tebal, interior bersih bebas bau rokok.',
                'features' => ['Cruise Control', 'Electric Parking Brake + Auto Hold', 'Ground Clearance 220 mm', 'Kamera Mundur'],
                'requirements' => [
                    'E-KTP Asli penyewa',
                    'SIM A Aktif',
                    'Deposit jaminan Rp 200.000 (Refundable 100%)',
                    'Denda keterlambatan Rp 35.000 / jam',
                ],
            ],
        ];

        $createdVehicles = [];
        foreach ($vehiclesData as $data) {
            $createdVehicles[$data['slug']] = Vehicle::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 4. Initial Bookings (For Task 3.1 & 3.4 testing)
        $beat = $createdVehicles['honda-beat-cbs'];
        $brio = $createdVehicles['honda-brio-rs-matic'];
        $avanza = $createdVehicles['toyota-all-new-avanza'];
        $nmax = $createdVehicles['yamaha-nmax-155-connected'];

        $bookingsData = [
            [
                'booking_code' => 'XR-20261014-AB12',
                'vehicle_id' => $beat->id,
                'customer_name' => 'Ahmad Fauzi',
                'customer_phone' => '0812-3456-7890',
                'customer_type' => 'mahasiswa',
                'start_date' => '2026-10-15 09:00:00',
                'duration_type' => 'daily',
                'duration_count' => 3,
                'delivery_option' => 'garage',
                'delivery_fee' => 0,
                'deposit_amount' => 50000,
                'total_price' => 305000,
                'status' => 'pending',
                'notes' => 'Tolong sediakan helm ukuran L dan jas hujan ponco warna gelap.',
                'pickup_address' => 'Garasi Pusat X-RENT, Jl. Cilandak KKO No. 45, Jakarta Selatan',
                'payment_status' => 'Belum Dibayar',
            ],
            [
                'booking_code' => 'XR-20261014-CD34',
                'vehicle_id' => $brio->id,
                'customer_name' => 'Jessica Stephanie',
                'customer_phone' => '0813-9876-5432',
                'customer_type' => 'wisatawan',
                'start_date' => '2026-10-15 11:00:00',
                'duration_type' => 'daily',
                'duration_count' => 2,
                'delivery_option' => 'delivery',
                'delivery_fee' => 25000,
                'deposit_amount' => 200000,
                'total_price' => 785000,
                'status' => 'confirmed',
                'notes' => 'Mohon mobil disemprot disinfektan wangi aroma kopi / citrus.',
                'pickup_address' => 'Hotel Artotel Thamrin, Lobby Utama, Menteng, Jakarta Pusat',
                'payment_status' => 'DP Terbayar 50% (Rp 392.500)',
            ],
            [
                'booking_code' => 'XR-20261014-EF56',
                'vehicle_id' => $avanza->id,
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '0817-2233-4455',
                'customer_type' => 'pekerja',
                'start_date' => '2026-10-13 10:00:00',
                'duration_type' => 'daily',
                'duration_count' => 3,
                'delivery_option' => 'garage',
                'delivery_fee' => 0,
                'deposit_amount' => 200000,
                'total_price' => 1340000,
                'status' => 'ongoing',
                'notes' => 'Rute dinas kantor menuju Kawasan Industri Karawang & Bandung.',
                'pickup_address' => 'Garasi Pusat X-RENT, Jl. Cilandak KKO No. 45, Jakarta Selatan',
                'payment_status' => 'Lunas 100% (Termasuk Asuransi All-Risk)',
            ],
            [
                'booking_code' => 'XR-20261014-GH78',
                'vehicle_id' => $nmax->id,
                'customer_name' => 'Rian Hidayat',
                'customer_phone' => '0819-7788-9900',
                'customer_type' => 'umum',
                'start_date' => '2026-10-11 08:00:00',
                'duration_type' => 'daily',
                'duration_count' => 3,
                'delivery_option' => 'garage',
                'delivery_fee' => 0,
                'deposit_amount' => 50000,
                'total_price' => 485000,
                'status' => 'completed',
                'notes' => 'Pengembalian tepat waktu, helm & jas hujan lengkap tanpa cacat.',
                'pickup_address' => 'Garasi Pusat X-RENT, Cilandak',
                'payment_status' => 'Selesai & Deposit Direfund Penuh (Rp 50.000)',
            ],
        ];

        foreach ($bookingsData as $bookingItem) {
            Booking::updateOrCreate(
                ['booking_code' => $bookingItem['booking_code']],
                $bookingItem
            );
        }
    }
}
