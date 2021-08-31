<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'caminho' => $faker->image(public_path('storage/images'), 640, 480, 'cats'),
        'descricao' => $faker->sentence(5, true)
    ];
});
