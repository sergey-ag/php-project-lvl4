<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\Task;

class TasksTest extends TestCase
{
    public function testTaskGetFiltered()
    {
        $filter = [
            'userFilter' => [],
            'statusFilter' => [],
            'tagFilter' => []
        ];
        $tasks = Task::getFiltered($filter);
        $this->assertEquals($tasks->count(), 2);
        
        $filter = [
            'userFilter' => [$this->usersTestSet->first()->id],
            'statusFilter' => [],
            'tagFilter' => []
        ];
        $tasks = Task::getFiltered($filter);
        $this->assertEquals($tasks->count(), 1);
    }
}
