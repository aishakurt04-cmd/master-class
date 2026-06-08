<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function register_page_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '88005553535'
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
    }

    public function user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function login_fails_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct_password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function user_can_logout()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/logout');
        
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}