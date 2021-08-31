<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
      'nome' => $faker->name,
      'emprego' => $faker->jobTitle,
      'comentario' => $faker->sentences(3, true),
      'imagem_id' => $faker->numberBetween(1, 10)
    ];
});
