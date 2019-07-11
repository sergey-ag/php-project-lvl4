<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $usersTestSet;

    public function setUp(): void
    {
        parent::setUp();
        $this->usersTestSet = factory(User::class, 3)->create();
    }

    public function testGetUsersIndex()
    {
        $response = $this->actingAs($this->usersTestSet->first())
            ->get('/users')
            ->assertOk();
        $this->assertDatabaseHas('users', ['name' => $this->usersTestSet->first()->name]);
    }

    public function testGetUsersEdit()
    {
        $response = $this->actingAs($this->usersTestSet->first())->get('/users/edit');
        $response->assertOk();
    }

    public function testPutUsers()
    {
        $response = $this->actingAs($this->usersTestSet->first())
            ->from('/users/edit')
            ->put('/users', ['name' => 'Marty McFly', 'password' => null])
            ->assertRedirect('/');
        $this->assertDatabaseHas('users', ['name' => 'Marty McFly']);
    }

    public function testPutUsers2()
    {
        $response = $this->actingAs($this->usersTestSet->first())
            ->put('/users', [
                'name' => 'Emmet Brown',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword'
            ]);
        $this->assertDatabaseMissing('users', [
            'name' => $this->usersTestSet->first()->name,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
    }

    public function testPutUsers3()
    {
        $response = $this->actingAs($this->usersTestSet->first())
            ->from('/users/edit')
            ->put('/users', ['name' => null, 'password' => null])
            ->assertRedirect('/users/edit');
    }

    public function testDeleteUsers()
    {
        $response = $this->actingAs($this->usersTestSet->first())
            ->delete('/users');
        $this->assertDatabaseMissing('users', ['name' => $this->usersTestSet->first()->name]);
    }
}
