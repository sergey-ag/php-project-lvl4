<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    public function testGetTasksIndex()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/tasks')
            ->assertOk();
        $this->assertDatabaseHas('tasks', ['name' => $this->tasksTestSet[0]->name]);
    }

    public function testGetTasksIndexWithFilter()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get("/tasks?statusFilter[]={$this->taskStatusesTestSet->first()->id}")
            ->assertOk();
    }

    public function testGetTasksCreate()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/tasks/create')
            ->assertOk();
    }

    public function testPostTasksStore()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/tasks/create')
            ->post('/tasks', [
                'name' => 'NewTask',
                'description' => 'Some text description...',
                'status_id' => $this->taskStatusesTestSet->first()->id,
                'creator_id' => $this->usersTestSet->first()->id,
                'tags' => 'tag1, tag2',
                'assigned_to_id' => $this->usersTestSet->last()->id
            ])
            ->assertRedirect('/tasks');
        $this->assertDataBaseHas('tasks', ['name' => 'NewTask']);
        $this->assertDataBaseHas('tags', ['name' => 'tag1']);
    }

    public function testPostTasksStoreValidationFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/tasks/create')
            ->post('/tasks', [
                'name' => null,
                'description' => 'Some text description...',
                'status_id' => $this->taskStatusesTestSet->first()->id,
                'creator_id' => $this->usersTestSet->first()->id,
                'assigned_to_id' => $this->usersTestSet->last()->id
            ])
            ->assertRedirect('/tasks/create');
    }

    public function testGetTasksEdit()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get("/tasks/{$this->tasksTestSet[0]->id}/edit")
            ->assertOk();
    }

    public function testPutTasks()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from("/tasks/{$this->tasksTestSet[0]->id}/edit")
            ->put("/tasks/{$this->tasksTestSet[0]->id}", [
                'name' => 'UpdatedTaskName',
                'description' => 'Updated Text Descriptio...',
                'status_id' => $this->taskStatusesTestSet->first()->id,
                'tags' => 'tag3',
                'creator_id' => $this->usersTestSet->first()->id,
                'assigned_to_id' => $this->usersTestSet->last()->id
            ])
            ->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['name' => 'UpdatedTaskName']);
        $this->assertDataBaseHas('tags', ['name' => 'tag3']);
    }

    public function testPutTasksValidationFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from("/tasks/{$this->tasksTestSet[0]->id}/edit")
            ->put("/tasks/{$this->tasksTestSet[0]->id}", [
                'name' => null,
                'description' => 'Updated Text Description...',
                'status_id' => $this->taskStatusesTestSet->first()->id,
                'creator_id' => $this->usersTestSet->first()->id,
                'assigned_to_id' => $this->usersTestSet->last()->id
            ])
            ->assertRedirect("/tasks/{$this->tasksTestSet[0]->id}/edit");
    }

    public function testDeleteTasks()
    {
        $this->actingAs($this->usersTestSet->first())
            ->delete("/tasks/{$this->tasksTestSet[0]->id}");
        $this->assertDatabaseMissing('tasks', ['name' => $this->tasksTestSet[0]->name]);
    }
}
