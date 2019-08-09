<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\Task;

class TasksTest extends TestCase
{
    public function testTaskGetFilteredMultiple()
    {
        $user = $this->usersTestSet->first();
        
        $filter = [
            'userFilter' => [],
            'statusFilter' => [],
            'tagFilter' => []
        ];
        $tasks = Task::getFilteredMultiple($filter);
        $this->assertEquals($tasks->count(), 2);
        
        $filter = [
            'userFilter' => [$user->id],
            'statusFilter' => [],
            'tagFilter' => []
        ];
        $tasks = Task::getFilteredMultiple($filter);
        $this->assertEquals($tasks->count(), 1);
    }

    public function testTaskGetFiltered()
    {
        $user = $this->usersTestSet->first();

        $filter = [
            'userFilter' => null,
            'statusFilter' => null,
            'tagFilter' => null
        ];
        $tasks = Task::getFiltered($filter);
        $this->assertEquals($tasks->count(), 2);
        
        $filter = [
            'userFilter' => $user->id,
            'statusFilter' => null,
            'tagFilter' => null
        ];
        $tasks = Task::getFiltered($filter);
        $this->assertEquals($tasks->count(), 1);
    }
}
