<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\User;
use Craftworks\TaskManager\TaskStatus;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $testTaskStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class, 1)->create();
        $this->testTaskStatus = TaskStatus::create(['name' => 'TestTaskStatus']);
    }

    public function testGetTaskStatusesIndex()
    {
        $response = $this->actingAs($this->user->first())
            ->get('/task_statuses')
            ->assertOk();
        $this->assertDatabaseHas('task_statuses', ['name' => $this->testTaskStatus->name]);
    }

    public function testGetTaskStatusesCreate()
    {
        $response = $this->actingAs($this->user->first())
            ->get('/task_statuses/create')
            ->assertOk();
    }

    public function testPostTaskStatusesStore()
    {
        $response = $this->actingAs($this->user->first())
            ->from('/task_statuses/create')
            ->post('/task_statuses', ['name' => 'NewTaskStatus'])
            ->assertRedirect('/task_statuses');
        $this->assertDataBaseHas('task_statuses', ['name' => 'NewTaskStatus']);
    }

    public function testPostTaskStatusesStore2()
    {
        $response = $this->actingAs($this->user->first())
            ->from('/task_statuses/create')
            ->post('/task_statuses', ['name' => null])
            ->assertRedirect('/task_statuses/create');
    }
    
    public function testGetTaskStatusesEdit()
    {
        $response = $this
            ->actingAs($this->user->first())
            ->get("/task_statuses/{$this->testTaskStatus->id}/edit");
        $response->assertOk();
    }

    public function testPutTaskStatuses()
    {
        $response = $this->actingAs($this->user->first())
            ->from("/task_statuses/{$this->testTaskStatus->id}/edit")
            ->put("/task_statuses/{$this->testTaskStatus->id}", ['name' => 'UpdatedTaskStatus'])
            ->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'UpdatedTaskStatus']);
    }
    
    public function testPutTaskStatuses2()
    {
        $response = $this->actingAs($this->user->first())
            ->from("/task_statuses/{$this->testTaskStatus->id}/edit")
            ->put("/task_statuses/{$this->testTaskStatus->id}", ['name' => null])
            ->assertRedirect("/task_statuses/{$this->testTaskStatus->id}/edit");
    }
    
    public function testDeleteTaskStatuses()
    {
        $response = $this->actingAs($this->user->first())
            ->delete("/task_statuses/{$this->testTaskStatus->id}");
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->testTaskStatus->name]);
    }
}
