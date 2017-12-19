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

$factory->define(\App\Models\Response::class, function (Faker $faker) {

    return [
        'question_id' => factory(\App\Models\Question::class)->create()->id,
        'type' => 'txt',
        'txt' => $faker->sentence(1)
    ];
});