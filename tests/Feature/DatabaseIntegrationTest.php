<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseIntegrationTest extends TestCase
{
    /**
     * Test admin user seeded and password matches.
     */
    public function test_admin_user_is_seeded(): void
    {
        $admin = User::where('email', 'admin@xrent.id')->first();

        $this->assertNotNull($admin);
        $this->assertEquals('Admin X-RENT', $admin->name);
        $this->assertTrue(Hash::check('password', $admin->password));
    }

    /**
     * Test categories and vehicles relation.
     */
    public function test_category_vehicles_relationship(): void
    {
        $category = Category::where('slug', 'matic-ekonomis')->first();

        $this->assertNotNull($category);
        $this->assertGreaterThanOrEqual(1, $category->vehicles()->count());

        $vehicle = $category->vehicles()->first();
        $this->assertEquals($category->id, $vehicle->category->id);
    }

    /**
     * Test vehicle accessors and attributes.
     */
    public function test_vehicle_accessors(): void
    {
        $vehicle = Vehicle::where('slug', 'honda-beat-cbs')->first();

        $this->assertNotNull($vehicle);
        $this->assertTrue($vehicle->is_available);
        $this->assertEquals('Tersedia', $vehicle->status_label);
        $this->assertIsArray($vehicle->prices);
        $this->assertEquals(85000, $vehicle->prices['daily']);
        $this->assertEquals('Matic Ekonomis', $vehicle->category_label);
        $this->assertIsArray($vehicle->gallery);
        $this->assertIsArray($vehicle->specs);
    }

    /**
     * Test booking relationship and step index.
     */
    public function test_booking_relationship_and_step_index(): void
    {
        $booking = Booking::where('booking_code', 'XR-20261014-AB12')->first();

        $this->assertNotNull($booking);
        $this->assertEquals(1, $booking->step_index);
        $this->assertEquals('Menunggu Konfirmasi', $booking->status_label);
        $this->assertEquals('honda-beat-cbs', $booking->vehicle->slug);

        $confirmed = Booking::where('booking_code', 'XR-20261014-CD34')->first();
        $this->assertEquals(2, $confirmed->step_index);

        $ongoing = Booking::where('booking_code', 'XR-20261014-EF56')->first();
        $this->assertEquals(3, $ongoing->step_index);

        $completed = Booking::where('booking_code', 'XR-20261014-GH78')->first();
        $this->assertEquals(4, $completed->step_index);
    }
}
