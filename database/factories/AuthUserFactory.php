<?php

namespace Database\Factories;

use App\Models\AuthUser;
use Faker\Generator as Faker;

$factory->define(AuthUser::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('password'), // Set a default password
        'mobile' => $faker->phoneNumber,
        'role' => 0, // Default role is user
    ];
});
