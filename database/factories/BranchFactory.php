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

$factory->define(\App\Form::class, function (Faker $faker) {

    return [
        'encoding_id' => null,
        'name' => $faker->words(2, true),
        'description' => $faker->paragraph(2)
    ];
});