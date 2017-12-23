<?php

use App\Form;
use App\User;
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

$factory->define(\App\Encoding::class, function (Faker $faker) {

    return [
        'form_id' => factory(Form::class)->create()->id,
        'owner_id' => factory(User::class)->create()->id,
        'type' => 'simple',
        'publication_id' => factory(\App\Publication::class)->create()->id
    ];
});