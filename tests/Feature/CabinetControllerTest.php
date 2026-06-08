<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CabinetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cabinet_displays_users_master_classes()
    {
        $leader = User::factory()->create(['role' => 'leader']);
        $craft = Craft::create(['name' => 'Тест', 'description' => 'Тест']);

        $masterClass = MasterClass::create([
            'craft_id' => $craft->id,
            'leader_id' => $leader->id,
            'name' => 'Мой мастер-класс',
            'description' => 'Описание',
            'date' => now()->addDays(5),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 0,
            'price' => 1000,
        ]);

        $response = $this->actingAs($leader)->get('/cabinet');

        $response->assertStatus(200);
        $response->assertSee('Мой мастер-класс');
    }

    public function test_cabinet_shows_participants_for_master_class()
    {
        $leader = User::factory()->create(['role' => 'leader']);
        $craft = Craft::create(['name' => 'Тест', 'description' => 'Тест']);
        $participant = User::factory()->create(['role' => 'visitor']);

        $masterClass = MasterClass::create([
            'craft_id' => $craft->id,
            'leader_id' => $leader->id,
            'name' => 'Тестовый класс',
            'description' => 'Описание',
            'date' => now()->addDays(5),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 1,
            'price' => 1000,
        ]);

        // Зарегистрировать участника
        $this->actingAs($participant)->post("/registration/{$masterClass->id}", ['action' => 'confirm']);

        $response = $this->actingAs($leader)->get('/cabinet');

        $response->assertStatus(200);
        $response->assertSee($participant->name);
        $response->assertSee($participant->email);
    }
}
