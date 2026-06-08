<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_master_class_page_is_forbidden_for_visitors()
    {
        $visitor = User::factory()->create(['role' => 'visitor']);

        $response = $this->actingAs($visitor)->get('/master-class/create');

        $response->assertStatus(403);
    }

    public function test_create_master_class_page_is_accessible_for_leaders()
    {
        $leader = User::factory()->create(['role' => 'leader']);
        Craft::factory()->create();

        $response = $this->actingAs($leader)->get('/master-class/create');

        $response->assertStatus(200);
    }

    public function test_cabinet_page_is_forbidden_for_visitors()
    {
        $visitor = User::factory()->create(['role' => 'visitor']);

        $response = $this->actingAs($visitor)->get('/cabinet');

        $response->assertStatus(403);
    }

    public function test_cabinet_page_is_accessible_for_leaders()
    {
        $leader = User::factory()->create(['role' => 'leader']);

        $response = $this->actingAs($leader)->get('/cabinet');

        $response->assertStatus(200);
    }

    public function test_home_page_is_accessible_for_guests()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
