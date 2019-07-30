<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\TaskStatus;

class TaskStatusTest extends TestCase
{
    public function testGetTaskStatusesIndex()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/task_statuses')
            ->assertOk();
        $this->assertDatabaseHas('task_statuses', ['name' => $this->taskStatusesTestSet->first()->name]);
    }

    public function testGetTaskStatusesCreate()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/task_statuses/create')
            ->assertOk();
    }

    public function testPostTaskStatusesStore()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/task_statuses/create')
            ->post('/task_statuses', ['name' => 'NewTaskStatus'])
            ->assertRedirect('/task_statuses');
        $this->assertDataBaseHas('task_statuses', ['name' => 'NewTaskStatus']);
    }

    public function testPostTaskStatusesStoreValidationFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from('/task_statuses/create')
            ->post('/task_statuses', ['name' => null])
            ->assertRedirect('/task_statuses/create');
    }
    
    public function testGetTaskStatusesEdit()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get("/task_statuses/{$this->taskStatusesTestSet->first()->id}/edit")
            ->assertOk();
    }

    public function testPutTaskStatuses()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from("/task_statuses/{$this->taskStatusesTestSet->first()->id}/edit")
            ->put("/task_statuses/{$this->taskStatusesTestSet->first()->id}", ['name' => 'UpdatedTaskStatus'])
            ->assertRedirect('/task_statuses');
        $this->assertDatabaseHas('task_statuses', ['name' => 'UpdatedTaskStatus']);
    }
    
    public function testPutTaskStatusesVakidationFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from("/task_statuses/{$this->taskStatusesTestSet->first()->id}/edit")
            ->put("/task_statuses/{$this->taskStatusesTestSet->first()->id}", ['name' => null])
            ->assertRedirect("/task_statuses/{$this->taskStatusesTestSet->first()->id}/edit");
    }
    
    public function testDeleteTaskStatuses()
    {
        TaskStatus::find($this->taskStatusesTestSet->first()->id)->tasks
            ->each(function ($task, $key) {
                $task->tags()->detach();
                $task->delete();
            });

        $this->actingAs($this->usersTestSet->first())
            ->delete("/task_statuses/{$this->taskStatusesTestSet->first()->id}");
        $this->assertDatabaseMissing('task_statuses', ['name' => $this->taskStatusesTestSet->first()->name]);
    }

    public function testDeleteTaskStatusesFail()
    {
        $this->actingAs($this->usersTestSet->first())
            ->from("/task_statuses/{$this->taskStatusesTestSet->first()->id}/edit")
            ->delete("/task_statuses/{$this->taskStatusesTestSet->first()->id}")
            ->assertRedirect("/task_statuses/{$this->taskStatusesTestSet->first()->id}/edit");
        $this->assertDatabaseHas('task_statuses', ['name' => $this->taskStatusesTestSet->first()->name]);
    }
}
