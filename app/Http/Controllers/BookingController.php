<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * Display the Track Booking page with live data from MySQL (Task 3.4 roadmap.md).
     */
    public function track(Request $request): Response
    {
        $code = strtoupper(trim((string) $request->query('code', '')));

        // Fetch all bookings from MySQL database
        $dbBookings = Booking::with('vehicle.category')->get();

        // Find active booking by code, or fallback to first/latest
        $matchedBooking = null;
        if (!empty($code)) {
            $matchedBooking = $dbBookings->first(fn ($b) => strtoupper($b->booking_code) === $code);
        }

        $rawActive = $matchedBooking ?: $dbBookings->first();

        // Transform bookings for frontend
        $allBookingsFormatted = $dbBookings->map(fn ($b) => self::formatBookingForFrontend($b))->values()->all();
        $activeFormatted = $rawActive ? self::formatBookingForFrontend($rawActive) : null;

        // Dynamic sample pills from database
        $sampleCodes = $dbBookings->map(function ($b) {
            $label = match ($b->status) {
                'pending' => 'Pending (Menunggu)',
                'confirmed' => 'Confirmed (Siap Diambil)',
                'ongoing' => 'Ongoing (Sedang Disewa)',
                'completed' => 'Completed (Selesai)',
                default => ucfirst($b->status),
            };

            return [
                'code' => $b->booking_code,
                'status' => $b->status,
                'label' => "{$b->booking_code} ({$label})",
                'badge' => $b->status_label,
                'vehicle' => $b->vehicle ? $b->vehicle->name : 'Unit Kendaraan',
            ];
        })->values()->all();

        return Inertia::render('TrackBooking', [
            'initialCode' => $code,
            'activeBooking' => $activeFormatted,
            'allBookings' => $allBookingsFormatted,
            'sampleCodes' => $sampleCodes,
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
     * Store online booking form into MySQL database & prepare WhatsApp Admin confirmation (Task 3.3 roadmap.md).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_slug' => 'nullable|string',
            'vehicle_name' => 'nullable|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:30',
            'customer_category' => 'nullable|string|max:50',
            'start_date' => 'required|string|max:50',
            'duration_type' => 'required|string|in:daily,weekly,monthly',
            'duration_count' => 'required|integer|min:1',
            'pickup_option' => 'required|string|in:garage,delivery',
            'notes' => 'nullable|string|max:500',
        ]);

        // Find the vehicle in database
        $vehicle = null;
        if (!empty($validated['vehicle_id'])) {
            $vehicle = Vehicle::find($validated['vehicle_id']);
        } elseif (!empty($validated['vehicle_slug'])) {
            $vehicle = Vehicle::where('slug', $validated['vehicle_slug'])->first();
        } elseif (!empty($validated['vehicle_name'])) {
            $vehicle = Vehicle::where('name', 'like', '%' . $validated['vehicle_name'] . '%')->first();
        }

        if (!$vehicle) {
            $vehicle = Vehicle::first();
        }

        // Calculate accurate price on backend
        $unitRate = match ($validated['duration_type']) {
            'weekly' => (int) $vehicle->price_weekly,
            'monthly' => (int) $vehicle->price_monthly,
            default => (int) $vehicle->price_daily,
        };

        $durationCount = (int) $validated['duration_count'];
        $subtotal = $unitRate * $durationCount;
        $deliveryFee = $validated['pickup_option'] === 'delivery' ? 25000 : 0;
        $deposit = $vehicle->type === 'mobil' ? 200000 : 50000;
        $totalPrice = $subtotal + $deliveryFee + $deposit;

        // Generate unique booking code XR-YYYYMMDD-XXXX
        $bookingCode = 'XR-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // Format start date
        $startDateTime = date('Y-m-d H:i:s', strtotime($validated['start_date']) ?: time());

        // Save into MySQL database
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'vehicle_id' => $vehicle->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_type' => $validated['customer_category'] ?? 'umum',
            'start_date' => $startDateTime,
            'duration_type' => $validated['duration_type'],
            'duration_count' => $durationCount,
            'delivery_option' => $validated['pickup_option'],
            'delivery_fee' => $deliveryFee,
            'deposit_amount' => $deposit,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'pickup_address' => $validated['pickup_option'] === 'delivery'
                ? ($validated['notes'] ?: 'Alamat antar diinformasikan ke WhatsApp')
                : 'Garasi Pusat X-RENT, Cilandak',
            'payment_status' => 'Belum Dibayar',
        ]);

        // Generate WhatsApp confirmation message link
        $waNumber = '6281288889999';
        $formattedDate = date('d M Y, H:i', strtotime($startDateTime));
        $durationLabel = "{$durationCount} " . ucfirst($validated['duration_type']);
        $pickupLabel = $validated['pickup_option'] === 'delivery'
            ? 'Layanan Antar Drop-Off (+Rp 25.000)'
            : 'Ambil di Garasi (Rp 0)';

        $waMsg = "*KONFIRMASI BOOKING X-RENT: {$bookingCode}*\n"
            . "----------------------------------------\n"
            . "*Unit:* {$vehicle->name}\n"
            . "*Penyewa:* {$booking->customer_name} ({$booking->customer_phone})\n"
            . "*Kategori:* {$booking->customer_type}\n"
            . "*Mulai Sewa:* {$formattedDate} WIB\n"
            . "*Durasi:* {$durationLabel}\n"
            . "*Opsi Pengambilan:* {$pickupLabel}\n"
            . "*Total Biaya Estimasi:* Rp " . number_format($totalPrice, 0, ',', '.') . "\n";

        if ($booking->notes) {
            $waMsg .= "*Catatan / Alamat:* {$booking->notes}\n";
        }

        $waMsg .= "----------------------------------------\n"
            . "Mohon proses verifikasi identitas dan konfirmasi ketersediaan unit saya. Terima kasih!";

        $whatsappUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMsg);

        return redirect()->route('booking.track', ['code' => $bookingCode])
            ->with('success', "Pemesanan berhasil diproses! Kode Booking Anda: {$bookingCode}. Silakan lanjutkan konfirmasi ke WhatsApp Admin.")
            ->with('whatsapp_url', $whatsappUrl);
    }

    /**
     * Helper to format a Booking Eloquent model into the frontend view structure.
     */
    private static function formatBookingForFrontend(Booking $booking): array
    {
        $vehicle = $booking->vehicle;

        $startDateStr = $booking->start_date ? $booking->start_date->format('d M Y • H:i') . ' WIB' : '15 Okt 2026 • 09:00 WIB';

        // Calculate end date
        $durationDays = match ($booking->duration_type) {
            'weekly' => $booking->duration_count * 7,
            'monthly' => $booking->duration_count * 30,
            default => $booking->duration_count,
        };
        $endDate = $booking->start_date ? $booking->start_date->copy()->addDays($durationDays) : null;
        $endDateStr = $endDate ? $endDate->format('d M Y • H:i') . ' WIB' : '18 Okt 2026 • 09:00 WIB';

        $durationLabel = "{$booking->duration_count} " . match ($booking->duration_type) {
            'weekly' => 'Minggu (Mingguan)',
            'monthly' => 'Bulan (Bulanan)',
            default => 'Hari (Harian)',
        };

        $statusDesc = match ($booking->status) {
            'pending' => 'Admin kami sedang memverifikasi data identitas (KTP & SIM) dan ketersediaan unit di sistem garasi.',
            'confirmed' => 'Unit telah disiapkan, dicek teknis, dan siap untuk serah terima di garasi atau diantar ke lokasi Anda.',
            'ongoing' => 'Unit kendaraan saat ini sedang aktif digunakan oleh penyewa. Nikmati perjalanan aman bersama X-RENT!',
            'completed' => 'Unit telah diterima kembali oleh staf garasi dalam kondisi baik. Deposit jaminan telah direfund 100%.',
            'cancelled' => 'Pemesanan ini telah dibatalkan.',
            default => 'Status pemesanan sedang diproses.',
        };

        // Timeline steps
        $stepIndex = $booking->step_index;
        $timeline = [
            [
                'step' => 1,
                'name' => 'Pesanan Dibuat',
                'desc' => 'Formulir online berhasil dikirim ke server X-RENT.',
                'time' => $booking->created_at ? $booking->created_at->format('d M Y, H:i') . ' WIB' : 'Baru saja',
                'status' => $stepIndex >= 1 ? ($stepIndex > 1 ? 'completed' : 'current') : 'upcoming',
                'icon' => 'receipt_long',
            ],
            [
                'step' => 2,
                'name' => 'Verifikasi Identitas & Unit',
                'desc' => 'Admin memeriksa keabsahan KTP & SIM penyewa serta kesiapan unit.',
                'time' => $stepIndex > 2 ? 'Selesai' : ($stepIndex === 2 ? 'Sedang berlangsung...' : 'Menunggu verifikasi'),
                'status' => $stepIndex >= 2 ? ($stepIndex > 2 ? 'completed' : 'current') : 'upcoming',
                'icon' => 'verified_user',
            ],
            [
                'step' => 3,
                'name' => 'Pengambilan / Serah Terima Unit',
                'desc' => $booking->delivery_option === 'delivery'
                    ? 'Unit diantar drop-off ke lokasi tujuan penyewa.'
                    : 'Penyewa datang ke Garasi Pusat untuk serah terima unit & kunci.',
                'time' => "Est. {$startDateStr}",
                'status' => $stepIndex >= 3 ? ($stepIndex > 3 ? 'completed' : 'current') : 'upcoming',
                'icon' => $booking->delivery_option === 'delivery' ? 'local_shipping' : 'key',
            ],
            [
                'step' => 4,
                'name' => 'Selesai & Pengembalian Deposit',
                'desc' => 'Unit dikembalikan utuh, deposit jaminan direfund seketika 100%.',
                'time' => "Est. {$endDateStr}",
                'status' => $stepIndex === 4 ? 'completed' : 'upcoming',
                'icon' => 'task_alt',
            ],
        ];

        $subtotal = $booking->total_price - $booking->delivery_fee - $booking->deposit_amount;

        return [
            'id' => $booking->id,
            'code' => $booking->booking_code,
            'status' => $booking->status,
            'status_label' => $booking->status_label,
            'status_desc' => $statusDesc,
            'step_index' => $stepIndex,
            'created_at' => $booking->created_at ? $booking->created_at->format('d M Y, H:i') . ' WIB' : '14 Okt 2026, 14:30 WIB',
            'customer_name' => $booking->customer_name,
            'customer_phone' => $booking->customer_phone,
            'customer_category' => ucfirst($booking->customer_type),
            'vehicle' => [
                'id' => $vehicle ? $vehicle->id : 1,
                'slug' => $vehicle ? $vehicle->slug : 'honda-beat-cbs',
                'name' => $vehicle ? $vehicle->name : 'Unit Kendaraan',
                'brand' => $vehicle ? $vehicle->brand : 'Honda',
                'type' => $vehicle ? $vehicle->type : 'motor',
                'plate' => $vehicle ? $vehicle->license_plate : 'B 1234 XYZ',
                'transmission' => $vehicle ? $vehicle->transmission : 'Matic',
                'image' => $vehicle ? $vehicle->image : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
            ],
            'rental_period' => [
                'start_datetime' => $startDateStr,
                'end_datetime' => $endDateStr,
                'duration_type' => $booking->duration_type,
                'duration_label' => $durationLabel,
                'duration_count' => $booking->duration_count,
            ],
            'pickup' => [
                'method' => $booking->delivery_option,
                'label' => $booking->delivery_option === 'delivery'
                    ? 'Layanan Antar ke Lokasi (Drop-Off)'
                    : 'Ambil Sendiri di Garasi Pusat Cilandak',
                'fee' => (int) $booking->delivery_fee,
                'address' => $booking->pickup_address ?: 'Garasi Pusat X-RENT, Jl. Cilandak KKO No. 45, Jakarta Selatan',
                'contact_person' => 'Koordinator Garasi: Mas Hendra • 0812-8888-9999',
            ],
            'payment' => [
                'unit_rate' => $booking->duration_count > 0 ? (int) ($subtotal / $booking->duration_count) : 0,
                'subtotal_rent' => (int) $subtotal,
                'delivery_fee' => (int) $booking->delivery_fee,
                'deposit_amount' => (int) $booking->deposit_amount,
                'total_payable' => (int) $booking->total_price,
                'status' => $booking->payment_status ?: 'Belum Dibayar',
                'method' => 'Transfer Bank / QRIS / Tunai di Garasi',
                'is_paid' => $booking->status !== 'pending',
            ],
            'notes' => $booking->notes,
            'timeline' => $timeline,
        ];
    }
}
