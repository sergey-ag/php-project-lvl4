<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Craftworks\TaskManager\Tag;
use Craftworks\TaskManager\Task;

class TagsTest extends TestCase
{
    public function testTagGetIds()
    {
        $tags = $this->tagsTestSet
            ->map(function ($tag, $key) {
                return $tag->name;
            })
            ->toArray();
        $tagsString = implode(', ', $tags);
        $expected = $this->tagsTestSet
            ->map(function ($tag, $key) {
                return $tag->id;
            })
            ->toArray();
        $this->assertEquals(Tag::getIds($tagsString), $expected);
    }

    public function testTagGetIdsEmpty()
    {
        $this->assertEquals(Tag::getIds(''), []);
    }
}
