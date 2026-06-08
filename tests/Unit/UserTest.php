<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_be_created()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'phone' => '88005553535',
            'role' => 'visitor'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
    }
    /** @test */
    public function leader_role_check_works()
    {
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'password' => bcrypt('password'),
            'phone' => '88005553535',
            'role' => 'visitor'
        ]);

        $leader = User::create([
            'name' => 'Leader',
            'email' => 'leader@example.com',
            'password' => bcrypt('password'),
            'phone' => '88005553536',
            'role' => 'leader'
        ]);

        $this->assertFalse($visitor->isLeader());
        $this->assertTrue($leader->isLeader());
    }
    /** @test */
    public function user_has_master_classes_relation()
    {
        $user = User::factory()->create(['role' => 'leader']);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->masterClasses());
    }
    /** @test */
    public function user_has_registered_master_classes_relation()
    {
        $user = User::factory()->create();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $user->registeredMasterClasses());
    }
}