<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\MasterClass;
use App\Models\User;
use App\Models\Craft;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterClassTest extends TestCase
{
    use RefreshDatabase;

    private $leader;
    private $craft;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->leader = User::factory()->create(['role' => 'leader']);
        $this->craft = Craft::factory()->create();
    }
    /** @test */
    public function master_class_can_be_created()
    {
        $masterClass = MasterClass::create([
            'craft_id' => $this->craft->id,
            'leader_id' => $this->leader->id,
            'name' => 'Test Master Class',
            'description' => 'Test Description',
            'date' => '2026-12-31',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 0,
            'price' => 1000
        ]);

        $this->assertDatabaseHas('master_classes', [
            'name' => 'Test Master Class'
        ]);
    }
    /** @test */
    public function has_free_places_returns_correct_value()
    {
        $masterClass = MasterClass::create([
            'craft_id' => $this->craft->id,
            'leader_id' => $this->leader->id,
            'name' => 'Test',
            'description' => 'Test',
            'date' => '2026-12-31',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 5,
            'price' => 1000
        ]);

        $this->assertTrue($masterClass->hasFreePlaces());

        $masterClass->current_participants = 10;
        $this->assertFalse($masterClass->hasFreePlaces());
    }
    /** @test */
    public function get_available_places_returns_correct_number()
    {
        $masterClass = MasterClass::create([
            'craft_id' => $this->craft->id,
            'leader_id' => $this->leader->id,
            'name' => 'Test',
            'description' => 'Test',
            'date' => '2026-12-31',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 3,
            'price' => 1000
        ]);

        $this->assertEquals(7, $masterClass->getAvailablePlaces());
    }
    /** @test */
    public function master_class_belongs_to_craft()
    {
        $masterClass = MasterClass::factory()->create([
            'craft_id' => $this->craft->id,
            'leader_id' => $this->leader->id
        ]);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $masterClass->craft());
    }
    /** @test */
    public function master_class_belongs_to_leader()
    {
        $masterClass = MasterClass::factory()->create([
            'craft_id' => $this->craft->id,
            'leader_id' => $this->leader->id
        ]);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $masterClass->leader());
    }
}