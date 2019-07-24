<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Craftworks\TaskManager\Task;
use Faker\Generator as Faker;
use Craftworks\TaskManager\TaskStatus;
use Craftworks\TaskManager\User;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(6),
        'description' => $faker->text(),
        'status_id' => TaskStatus::make(['name' => 'FactoryTaskStatus'])->id,
        'creator_id' => factory(User::class)->make()->id,
        'assigned_to_id' => factory(User::class)->make()->id
    ];
});
