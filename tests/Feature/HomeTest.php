<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_renders_with_inertia(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('appName')
            ->has('cars')
            ->has('motors')
            ->has('faqs')
            ->has('garageLocations')
        );
    }
}
