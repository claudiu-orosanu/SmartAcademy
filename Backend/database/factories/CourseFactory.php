<?php

use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
    return [
        'name' => $faker->realText(20),
        'description' => $faker->realText(),
        'category' => $faker->randomElement(\App\Course::$courseCategories),
        'image' => $faker->text(), //TODO to be generated with $faker->image; it will contain the path to the image
        'price' => $faker->randomFloat(2, 0, 10000)
    ];
});
