<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->words(4, true),
        'description' => $faker->paragraph(3)
    ];
});