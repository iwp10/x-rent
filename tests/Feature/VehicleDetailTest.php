<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VehicleDetailTest extends TestCase
{
    /**
     * Test vehicle detail page renders for a valid motor slug.
     */
    public function test_vehicle_detail_page_renders_motor(): void
    {
        $response = $this->get('/armada/honda-beat-cbs');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('VehicleDetail')
            ->has('vehicle')
            ->where('vehicle.slug', 'honda-beat-cbs')
            ->where('vehicle.name', 'Honda Beat CBS eSP')
            ->has('vehicle.gallery', 4)
            ->has('vehicle.condition_notes')
            ->has('vehicle.requirements')
            ->has('relatedVehicles')
        );
    }

    /**
     * Test vehicle detail page renders for a valid car slug.
     */
    public function test_vehicle_detail_page_renders_car(): void
    {
        $response = $this->get('/armada/toyota-innova-zenix-hybrid');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('VehicleDetail')
            ->has('vehicle')
            ->where('vehicle.slug', 'toyota-innova-zenix-hybrid')
            ->where('vehicle.type', 'mobil')
            ->has('relatedVehicles')
        );
    }

    /**
     * Test vehicle detail page works when looked up by numeric id.
     */
    public function test_vehicle_detail_page_renders_by_id(): void
    {
        $response = $this->get('/armada/1');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('VehicleDetail')
            ->has('vehicle')
            ->where('vehicle.id', 1)
        );
    }

    /**
     * Test vehicle detail page returns 404 for invalid slug.
     */
    public function test_vehicle_detail_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/armada/unit-tidak-ditemukan');

        $response->assertStatus(404);
    }
}
