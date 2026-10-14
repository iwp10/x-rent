<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    /**
     * Test catalog page renders with Inertia and contains expected props.
     */
    public function test_catalog_page_renders_with_inertia(): void
    {
        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Catalog')
            ->has('vehicles', 8)
            ->has('categories')
            ->has('stats')
            ->where('initialCategory', 'all')
        );
    }

    /**
     * Test direct car filter route renders catalog with mobil-keluarga category.
     */
    public function test_catalog_cars_route(): void
    {
        $response = $this->get('/armada-mobil');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Catalog')
            ->has('vehicles')
            ->where('initialCategory', 'mobil-keluarga')
        );
    }

    /**
     * Test direct motor filter route renders catalog with matic-maxi category.
     */
    public function test_catalog_motors_route(): void
    {
        $response = $this->get('/armada-motor');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Catalog')
            ->has('vehicles')
            ->where('initialCategory', 'matic-maxi')
        );
    }
}
