<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\User;
use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CraftControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_craft_page_displays_craft_details()
    {
        $craft = Craft::create([
            'name' => 'Рисование',
            'description' => 'Мастер-классы по рисованию',
            'image' => null
        ]);

        $response = $this->get("/craft/{$craft->id}");
        
        $response->assertStatus(200);
        $response->assertSee('Рисование');
        $response->assertSee('Мастер-классы по рисованию');
    }

    public function test_craft_page_shows_master_classes()
    {
        $craft = Craft::create(['name' => 'Тест', 'description' => 'Тест']);
        $leader = User::factory()->create(['role' => 'leader']);
        
        $masterClass = MasterClass::create([
            'craft_id' => $craft->id,
            'leader_id' => $leader->id,
            'name' => 'Тестовый мастер-класс',
            'description' => 'Описание',
            'date' => now()->addDays(5),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_participants' => 10,
            'current_participants' => 0,
            'price' => 1000
        ]);

        $response = $this->get("/craft/{$craft->id}");
        
        $response->assertStatus(200);
        // Проверяем, что вид творчества отображается
        $response->assertSee('Тест');
        // Проверяем, что имя ведущего отображается (из фабрики)
        $response->assertSee($leader->name);
    }

    public function test_craft_page_returns_404_for_nonexistent_craft()
    {
        $response = $this->get("/craft/99999");
        $response->assertStatus(404);
    }
}