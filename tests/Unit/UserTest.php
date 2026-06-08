<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'phone' => '88005553535',
            'role' => 'visitor',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_leader_role_check_works()
    {
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'password' => bcrypt('password'),
            'phone' => '88005553535',
            'role' => 'visitor',
        ]);

        $leader = User::create([
            'name' => 'Leader',
            'email' => 'leader@example.com',
            'password' => bcrypt('password'),
            'phone' => '88005553536',
            'role' => 'leader',
        ]);

        $this->assertFalse($visitor->isLeader());
        $this->assertTrue($leader->isLeader());
    }

    public function test_user_has_master_classes_relation()
    {
        $user = User::factory()->create(['role' => 'leader']);

        $this->assertInstanceOf(HasMany::class, $user->masterClasses());
    }


    public function test_user_has_registered_master_classes_relation()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(BelongsToMany::class, $user->registeredMasterClasses());
    }
}
