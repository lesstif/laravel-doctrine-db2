<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Domain\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt('password'),
        'remember_token' => str_random(60),
    ];
});

$factory->define(App\Domain\Entities\Task::class, function (Faker\Generator $faker) {
    $users = app(App\Domain\Repositories\UserRepository::class)->all();

    return [
        'name' => $faker->sentence,
        'user' => $faker->randomElement(collect($users)->toArray())
    ];
});

