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

$factory->define(App\Models\User::class, function (Faker\Generator $faker)
{
    return [
        'name'           => $faker->name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Item::class, function (Faker\Generator $faker)
{
    return [
        'user_id'     => 1,
        'title'       => $faker->sentence,
        'description' => $faker->sentence,
        'price'       => 235,
        'active'      => true,
        'end_time'    => $faker->dateTimeBetween('now', '+ 2 weeks')->format('Y-m-d H:i:s'),
        'slug'        => str_slug($faker->sentence),
    ];
});
