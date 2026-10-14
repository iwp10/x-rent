<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class VehicleManagementController extends Controller
{
    /**
     * Display a listing of fleet vehicles with filter & search.
     */
    public function index(Request $request): Response
    {
        $query = Vehicle::with('category')->latest();

        // Search by name or license plate
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->query('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Filter by category
        if ($categorySlug = $request->query('kategori')) {
            if ($categorySlug !== 'all') {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            }
        }

        // Filter by type
        if ($type = $request->query('type')) {
            if ($type !== 'all') {
                $query->where('type', $type);
            }
        }

        $vehicles = $query->get();
        $categories = Category::all();

        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => $vehicles,
            'categories' => $categories,
            'filters' => [
                'q' => $request->query('q', ''),
                'status' => $request->query('status', 'all'),
                'kategori' => $request->query('kategori', 'all'),
                'type' => $request->query('type', 'all'),
            ],
        ]);
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'license_plate' => 'required|string|max:20',
            'engine_cc' => 'required|integer|min:50|max:5000',
            'transmission' => 'required|string|max:50',
            'helm_included' => 'required|integer|min:0|max:10',
            'status' => 'required|in:available,rented,maintenance',
            'price_daily' => 'required|integer|min:1000',
            'price_weekly' => 'required|integer|min:1000',
            'price_monthly' => 'required|integer|min:1000',
            'image' => 'nullable|string',
            'type' => 'required|in:motor,mobil',
            'brand' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'condition_notes' => 'nullable|string|max:1000',
            'facilities' => 'nullable|string|max:1000',
            'badge' => 'nullable|string|max:50',
            'subtitle' => 'nullable|string|max:255',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Vehicle::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $validated['slug'] = $slug;

        // Default image if empty
        if (empty($validated['image'])) {
            $validated['image'] = $validated['type'] === 'mobil'
                ? 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80'
                : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80';
        }

        // Default specs
        $validated['specs'] = [
            ['icon' => 'speed', 'label' => "{$validated['engine_cc']} cc"],
            ['icon' => 'settings', 'label' => $validated['transmission']],
            ['icon' => 'sports_motorsports', 'label' => "{$validated['helm_included']} Helm"],
            ['icon' => 'local_gas_station', 'label' => 'Bensin Irit'],
        ];

        Vehicle::create($validated);

        return redirect()->back()
            ->with('success', "Unit armada '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Update the specified vehicle.
     */
    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'license_plate' => 'required|string|max:20',
            'engine_cc' => 'required|integer|min:50|max:5000',
            'transmission' => 'required|string|max:50',
            'helm_included' => 'required|integer|min:0|max:10',
            'status' => 'required|in:available,rented,maintenance',
            'price_daily' => 'required|integer|min:1000',
            'price_weekly' => 'required|integer|min:1000',
            'price_monthly' => 'required|integer|min:1000',
            'image' => 'nullable|string',
            'type' => 'required|in:motor,mobil',
            'brand' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'condition_notes' => 'nullable|string|max:1000',
            'facilities' => 'nullable|string|max:1000',
            'badge' => 'nullable|string|max:50',
            'subtitle' => 'nullable|string|max:255',
        ]);

        $vehicle->update($validated);

        return redirect()->back()
            ->with('success', "Data unit '{$vehicle->name}' berhasil diperbarui.");
    }

    /**
     * Quick status update for a vehicle (available / rented / maintenance).
     */
    public function updateStatus(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:available,rented,maintenance',
        ]);

        $vehicle->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', "Status unit '{$vehicle->name}' berhasil diubah menjadi {$vehicle->status_label}.");
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $name = $vehicle->name;
        $vehicle->delete();

        return redirect()->back()
            ->with('success', "Unit armada '{$name}' berhasil dihapus dari sistem.");
    }
}
