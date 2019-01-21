<?php

use Faker\Generator as Faker;
use App\Models\Client;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'name'          => $faker->unique()->word,
        'type'   => $faker->sentence(),
        'type_document' => $faker->sentence(),
        'document' => $faker->sentence(),
        'email' => $faker->sentence(),
        'tel_ddd' => $faker->sentence(),
        'tel_number' => $faker->sentence(), 
    ];
});