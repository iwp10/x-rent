<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    /**
     * Display the fleet catalog page (Task 3.2 roadmap.md).
     */
    public function index(Request $request): Response
    {
        $initialType = $request->query('type', 'all'); // 'all' | 'motor' | 'mobil'
        $initialCategory = $request->query('kategori', 'all');

        // If navigated with type=mobil or type=motor
        if ($request->query('type') === 'mobil' || $request->is('armada-mobil')) {
            $initialType = 'mobil';
        } elseif ($request->query('type') === 'motor' || $request->is('armada-motor')) {
            $initialType = 'motor';
        }

        // Fetch all vehicles with categories from MySQL using Eloquent
        $vehicles = Vehicle::with('category')->get();

        // Dynamic categories from database
        $dbCategories = Category::all()->map(function ($cat) {
            return [
                'id' => $cat->slug,
                'label' => $cat->name,
            ];
        })->toArray();

        $categoryList = array_merge([
            ['id' => 'all', 'label' => 'Semua Kendaraan'],
            ['id' => 'motor', 'label' => 'Semua Motor'],
            ['id' => 'mobil', 'label' => 'Semua Mobil'],
        ], $dbCategories);

        return Inertia::render('Catalog', [
            'vehicles' => $vehicles,
            'initialType' => $initialType,
            'initialCategory' => $initialCategory,
            'stats' => [
                'total_units' => $vehicles->count() . '+ Unit',
                'service_guarantee' => '100% Prima',
                'insurance' => 'All-Risk',
            ],
            'categories' => $categoryList,
        ]);
    }

    /**
     * Display the vehicle detail page (Task 3.2 roadmap.md).
     */
    public function show(Request $request, string $slug): Response
    {
        $vehicle = Vehicle::with('category')
            ->where('slug', $slug)
            ->orWhere('id', is_numeric($slug) ? (int)$slug : 0)
            ->first();

        if (!$vehicle) {
            abort(404, 'Kendaraan tidak ditemukan di armada kami.');
        }

        // Get related vehicles (exclude current, take 3)
        $related = Vehicle::with('category')
            ->where('id', '!=', $vehicle->id)
            ->where(function ($query) use ($vehicle) {
                $query->where('category_id', $vehicle->category_id)
                    ->orWhere('type', $vehicle->type);
            })
            ->limit(3)
            ->get();

        if ($related->count() < 3) {
            $related = Vehicle::with('category')
                ->where('id', '!=', $vehicle->id)
                ->limit(3)
                ->get();
        }

        return Inertia::render('VehicleDetail', [
            'vehicle' => $vehicle,
            'relatedVehicles' => $related,
        ]);
    }
}
