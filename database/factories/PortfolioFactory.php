<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Portfolio;
use Faker\Generator as Faker;

$factory->define(Portfolio::class, function (Faker $faker) {
    return [
        'titulo' => $faker->sentence(3, true),
        'link' => $faker->url,
        'imagem_id' => $faker->numberBetween(1, 10)
    ];
});

$factory->afterCreating(Portfolio::class, function ($row, $faker) {
    $row->categorias()->attach(rand(1,5));
});
