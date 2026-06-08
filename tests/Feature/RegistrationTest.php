<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $leader;

    private $craft;

    private $masterClass;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'visitor']);
        $this->leader = User::factory()->create(['role' => 'leader']);
        $this->craft = Craft::factory()->create();

        $this->masterClass = MasterClass::create([
            'craft_id' => $this->craft->id,
            'leader_id' => $this->leader->id,
            'name' => 'Test Master Class',
            'description' => 'Test Description',
            'date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 5,
            'price' => 1000,
        ]);
    }

    public function test_user_can_register_for_master_class()
    {
        $response = $this->actingAs($this->user)
            ->post("/registration/{$this->masterClass->id}", [
                'action' => 'confirm',
            ]);

        $response->assertRedirect("/craft/{$this->craft->id}");
        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->user->id,
            'master_class_id' => $this->masterClass->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_user_cannot_register_twice_for_same_master_class()
    {
        $this->actingAs($this->user)
            ->post("/registration/{$this->masterClass->id}", ['action' => 'confirm']);

        $response = $this->actingAs($this->user)
            ->post("/registration/{$this->masterClass->id}", ['action' => 'confirm']);

        $response->assertRedirect("/craft/{$this->craft->id}");
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_register_for_full_master_class()
    {
        $this->masterClass->current_participants = $this->masterClass->max_participants;
        $this->masterClass->save();

        $response = $this->actingAs($this->user)
            ->post("/registration/{$this->masterClass->id}", ['action' => 'confirm']);

        $response->assertRedirect("/craft/{$this->craft->id}");
        $response->assertSessionHas('error');
    }

    public function test_registration_requires_authentication()
    {
        $response = $this->get("/registration/{$this->masterClass->id}/create");

        $response->assertRedirect('/login');
    }
}
