<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Craftworks\TaskManager\User;
use Craftworks\TaskManager\TaskStatus;
use Craftworks\TaskManager\Task;

class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $usersTestSet;
    protected $tasksTestSet;
    protected $taskStatusesTestSet;

    public function setUp(): void
    {
        parent::setUp();
        $this->usersTestSet = factory(User::class, 2)->create();
        $this->taskStatusesTestSet = factory(TaskStatus::class, 2)->create();
        
        $this->tasksTestSet[] = factory(Task::class)->create([
            'status_id' => $this->taskStatusesTestSet->first()->id,
            'creator_id' => $this->usersTestSet->first()->id,
            'assigned_to_id' => $this->usersTestSet->first()->id
        ]);
        $this->tasksTestSet[] = factory(Task::class)->create([
            'status_id' => $this->taskStatusesTestSet->first()->id,
            'creator_id' => $this->usersTestSet->first()->id,
            'assigned_to_id' => $this->usersTestSet->last()->id
        ]);
    }
}
