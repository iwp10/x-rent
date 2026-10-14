<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingManagementController extends Controller
{
    /**
     * Display a listing of incoming bookings with search and status filtering.
     */
    public function index(Request $request): Response
    {
        $query = Booking::with('vehicle.category')->latest();

        // Search by booking code, customer name, or phone
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter by booking status
        if ($status = $request->query('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $bookings = $query->get();

        // Status counts for tabs
        $statusCounts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'ongoing' => Booking::where('status', 'ongoing')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'statusCounts' => $statusCounts,
            'filters' => [
                'q' => $request->query('q', ''),
                'status' => $request->query('status', 'all'),
            ],
        ]);
    }

    /**
     * Update booking status (pending -> confirmed -> ongoing -> completed / cancelled).
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,ongoing,completed,cancelled',
        ]);

        $newStatus = $validated['status'];
        $booking->update([
            'status' => $newStatus,
            'payment_status' => match ($newStatus) {
                'pending' => 'Belum Dibayar',
                'confirmed' => 'DP Terbayar 50%',
                'ongoing' => 'Lunas 100%',
                'completed' => 'Selesai & Deposit Direfund',
                'cancelled' => 'Dibatalkan',
                default => $booking->payment_status,
            },
        ]);

        // Synchronize vehicle status if linked
        if ($booking->vehicle) {
            if ($newStatus === 'ongoing') {
                $booking->vehicle->update(['status' => 'rented']);
            } elseif (in_array($newStatus, ['completed', 'cancelled'])) {
                // Return vehicle to available if no other ongoing booking
                $hasOtherActive = Booking::where('vehicle_id', $booking->vehicle_id)
                    ->where('id', '!=', $booking->id)
                    ->where('status', 'ongoing')
                    ->exists();

                if (!$hasOtherActive) {
                    $booking->vehicle->update(['status' => 'available']);
                }
            }
        }

        return redirect()->back()
            ->with('success', "Status pesanan {$booking->booking_code} berhasil diperbarui menjadi {$booking->status_label}.");
    }
}
