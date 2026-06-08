<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Craft;
use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterClassAccessTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function create_master_class_page_is_forbidden_for_visitors()
    {
        $visitor = User::factory()->create(['role' => 'visitor']);
        
        $response = $this->actingAs($visitor)->get('/master-class/create');
        
        $response->assertStatus(403);
    }
    /** @test */
    public function create_master_class_page_is_accessible_for_leaders()
    {
        $leader = User::factory()->create(['role' => 'leader']);
        Craft::factory()->create();
        
        $response = $this->actingAs($leader)->get('/master-class/create');
        
        $response->assertStatus(200);
    }
    /** @test */
    public function cabinet_page_is_forbidden_for_visitors()
    {
        $visitor = User::factory()->create(['role' => 'visitor']);
        
        $response = $this->actingAs($visitor)->get('/cabinet');
        
        $response->assertStatus(403);
    }
    /** @test */
    public function cabinet_page_is_accessible_for_leaders()
    {
        $leader = User::factory()->create(['role' => 'leader']);
        
        $response = $this->actingAs($leader)->get('/cabinet');
        
        $response->assertStatus(200);
    }
    /** @test */
    public function home_page_is_accessible_for_guests()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}