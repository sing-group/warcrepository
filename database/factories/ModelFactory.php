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

// Definiendo los factories para registered users.
$factory->defineAs(App\User::class, 'registered', function (Faker\Generator $faker) {

    return [
        'uuid' => $faker->uuid,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role' => 'registered',
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
        'photo' => '',
        'verified' => 1,
    ];
});

$factory->defineAs(App\User::class, 'demo', function (Faker\Generator $faker) {

    return [
        'uuid' => $faker->uuid,
        'name' => 'demo',
        'email' => 'demo@demo.demo',
        'role' => 'demo',
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
        'photo' => '',
        'verified' => 1,
    ];
});

// Definiendo los factories para admin users (solo crearemos uno).
$factory->defineAs(App\User::class, 'admin', function (Faker\Generator $faker) {

    return [
        'uuid' => $faker->uuid,
        'name' => 'admin',
        'email' =>  'admin@admin.admin',
        'role' => 'admin',
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
        'photo' => '',
        'verified' => 1,
    ];
});

// Definiendo los factories para los corpus.
$factory->defineAs(App\Corpus::class, 'corpus', function (Faker\Generator $faker) {

    return [
        'uuid' => $faker->uuid,
        'name' => $faker->name,
        'path' => str_random(40),
        'user_id' => function (){
            // If we pass a custom user_id , it never runs.
            //return factory(\App\User::class)->create()->id;
            return factory('App\User', 'registered')->create()->id;
        },
    ];
});
