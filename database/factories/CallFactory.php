<?php

use App\Hook;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Call::class, function (Faker $faker) {
    return [
        'hook_id' => create(Hook::class)->id
    ];
});
