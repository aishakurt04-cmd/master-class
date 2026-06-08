<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_crafts_list()
    {
        $craft1 = Craft::create(['name' => 'Рисование', 'description' => 'Тест']);
        $craft2 = Craft::create(['name' => 'Лепка', 'description' => 'Тест']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Рисование');
        $response->assertSee('Лепка');
    }

    public function test_home_page_shows_user_registrations_when_authenticated()
    {
        $user = User::factory()->create(['role' => 'visitor']);
        $craft = Craft::create(['name' => 'Тест', 'description' => 'Тест']);
        $leader = User::factory()->create(['role' => 'leader']);

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

        // Регистрация пользователя
        $this->actingAs($user)->post("/registration/{$masterClass->id}", ['action' => 'confirm']);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Мой мастер-класс');
    }

    public function test_home_page_does_not_show_registrations_for_leaders()
    {
        $leader = User::factory()->create(['role' => 'leader']);

        $response = $this->actingAs($leader)->get('/');

        $response->assertStatus(200);
        // Лидеры не видят блок "Мои записи"
    }
}
