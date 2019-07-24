<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    public function testGetUsersIndex()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/users')
            ->assertOk();
        $this->assertDatabaseHas('users', ['name' => $this->usersTestSet->first()->name]);
    }

    public function testGetUsersEdit()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/users/edit')
            ->assertOk();
    }

    public function testPutUsers()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/users/edit')
            ->put('/users', ['name' => 'Marty McFly', 'password' => null])
            ->assertRedirect('/');
        $this->assertDatabaseHas('users', ['name' => 'Marty McFly']);
    }

    public function testPutUsersChangePassword()
    {
        $this->actingAs($this->usersTestSet->first())
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

    public function testPutUsersValidationFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/users/edit')
            ->put('/users', ['name' => null, 'password' => null])
            ->assertRedirect('/users/edit');
    }

    public function testDeleteUsers()
    {
        User::find($this->usersTestSet->last()->id)->tasks
            ->each(function ($task, $key) {
                $task->delete();
            });

        $this->actingAs($this->usersTestSet->last())
            ->delete('/users');
        $this->assertDatabaseMissing('users', ['name' => $this->usersTestSet->last()->name]);
    }

    public function testDeleteUsersFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/users/edit')
            ->delete('/users')
            ->assertRedirect('/users/edit');
        $this->assertDatabaseHas('users', ['name' => $this->usersTestSet->first()->name]);
    }
}
