<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;  // ← Добавить импорт

class ExampleTest extends TestCase
{
    use RefreshDatabase;  // ← Добавить эту строку

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
