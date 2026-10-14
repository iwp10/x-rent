<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Vehicle;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard overview.
     */
    public function index(): Response
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'rented_vehicles' => Vehicle::where('status', 'rented')->count(),
            'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'ongoing_bookings' => Booking::where('status', 'ongoing')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue' => (int) Booking::whereIn('status', ['confirmed', 'ongoing', 'completed'])->sum('total_price'),
        ];

        $recentBookings = Booking::with('vehicle.category')
            ->latest()
            ->take(6)
            ->get();

        $recentVehicles = Vehicle::with('category')
            ->latest()
            ->take(6)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'recentVehicles' => $recentVehicles,
        ]);
    }
}
