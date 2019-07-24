<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    public function testGetHome()
    {
        $this->get('/')
            ->assertRedirect('/login');
    }
    
    public function testGetDashboard()
    {
        $this->actingAs($this->usersTestSet->first())
            ->get('/')
            ->assertOk();
    }
}
