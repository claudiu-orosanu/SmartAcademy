<?php

use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'description' => $faker->text(),
        'category' => $faker->randomElement(\App\Course::$courseCategories),
        'imageUrl' => $faker->text(),
        'price' => $faker->randomFloat(2, 0, 10000)
    ];
});

$factory->define(App\Section::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'order_number' => 1,
    ];
});

$factory->define(App\Video::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'url' => $faker->url,
    ];
});

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'url' => $faker->url,
    ];
});
