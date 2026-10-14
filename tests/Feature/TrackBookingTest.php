<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TrackBookingTest extends TestCase
{
    /**
     * Test tracking page renders with default booking and sample pills.
     */
    public function test_track_booking_page_renders(): void
    {
        $response = $this->get('/cek-pesanan');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('TrackBooking')
            ->has('activeBooking')
            ->has('allBookings', 4)
            ->has('sampleCodes', 4)
            ->has('faqs')
            ->where('activeBooking.code', 'XR-20261014-AB12')
            ->where('activeBooking.status', 'pending')
        );
    }

    /**
     * Test tracking page with query code pre-selects the requested booking.
     */
    public function test_track_booking_with_code_query(): void
    {
        $response = $this->get('/cek-pesanan?code=XR-20261014-CD34');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('TrackBooking')
            ->where('initialCode', 'XR-20261014-CD34')
            ->where('activeBooking.code', 'XR-20261014-CD34')
            ->where('activeBooking.status', 'confirmed')
            ->where('activeBooking.vehicle.slug', 'honda-brio-rs-matic')
        );
    }

    /**
     * Test booking store simulation redirects to /cek-pesanan with code.
     */
    public function test_booking_store_simulation_redirects(): void
    {
        $response = $this->post('/booking', [
            'customer_name' => 'Fauzi Rahman',
            'customer_phone' => '081234567890',
            'customer_category' => 'mahasiswa',
            'start_date' => '2026-10-15 09:00',
            'duration_type' => 'daily',
            'duration_count' => 3,
            'pickup_option' => 'garage',
            'notes' => 'Tolong helm fullface',
            'vehicle_name' => 'Honda Beat CBS eSP',
        ]);

        $response->assertRedirect('/cek-pesanan?code=XR-20261014-AB12');
        $response->assertSessionHas('success');
    }
}
