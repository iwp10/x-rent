<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use App\Models\Vehicle;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    /**
     * Test admin login page renders for guest.
     */
    public function test_admin_login_page_renders(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Login')
        );
    }

    /**
     * Test unauthenticated guest cannot access admin dashboard.
     */
    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin can login with valid credentials.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@x-rent.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Test admin login fails with invalid password.
     */
    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@x-rent.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test authenticated admin can view dashboard.
     */
    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Dashboard')
            ->has('stats')
            ->has('recentBookings')
            ->has('recentVehicles')
        );
    }

    /**
     * Test authenticated admin can view vehicle management list.
     */
    public function test_admin_can_view_vehicles_management(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();

        $response = $this->actingAs($admin)->get('/admin/vehicles');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Vehicles/Index')
            ->has('vehicles')
            ->has('categories')
            ->has('filters')
        );
    }

    /**
     * Test admin can create a new vehicle.
     */
    public function test_admin_can_create_vehicle(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();
        $category = Category::where('slug', 'matic-ekonomis')->first();

        $response = $this->actingAs($admin)->post('/admin/vehicles', [
            'category_id' => $category->id,
            'name' => 'Honda Genio 110 CBS ISS',
            'license_plate' => 'B 9988 GNO',
            'engine_cc' => 110,
            'transmission' => 'Matic CVT',
            'helm_included' => 2,
            'status' => 'available',
            'price_daily' => 85000,
            'price_weekly' => 530000,
            'price_monthly' => 1750000,
            'type' => 'motor',
            'brand' => 'Honda',
            'year' => '2024',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('vehicles', [
            'name' => 'Honda Genio 110 CBS ISS',
            'license_plate' => 'B 9988 GNO',
            'status' => 'available',
        ]);
    }

    /**
     * Test admin can update vehicle status to rented or maintenance.
     */
    public function test_admin_can_update_vehicle_status(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();
        $vehicle = Vehicle::where('slug', 'honda-beat-cbs')->first();

        $response = $this->actingAs($admin)->patch("/admin/vehicles/{$vehicle->id}/status", [
            'status' => 'maintenance',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'status' => 'maintenance',
        ]);
    }

    /**
     * Test admin can view bookings list and status counts.
     */
    public function test_admin_can_view_bookings_management(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();

        $response = $this->actingAs($admin)->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Bookings/Index')
            ->has('bookings')
            ->has('statusCounts')
            ->has('filters')
        );
    }

    /**
     * Test admin can update booking status.
     */
    public function test_admin_can_update_booking_status(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();
        $booking = Booking::where('booking_code', 'XR-20261014-AB12')->first();

        $response = $this->actingAs($admin)->patch("/admin/bookings/{$booking->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Test admin can logout.
     */
    public function test_admin_can_logout(): void
    {
        $admin = User::where('email', 'admin@x-rent.test')->first();

        $response = $this->actingAs($admin)->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}
