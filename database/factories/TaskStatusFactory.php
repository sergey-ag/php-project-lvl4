<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Craftworks\TaskManager\TaskStatus;
use Faker\Generator as Faker;

$factory->define(TaskStatus::class, function (Faker $faker) {
    return [
        'name' => $faker->word()
    ];
});
