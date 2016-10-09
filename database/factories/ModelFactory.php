<?php

use App\Faculty;
use App\Lecture;
use App\Question;
use App\Subject;
use App\User;
use Carbon\Carbon;
use Faker\Generator;

$factory->define(User::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->word . '@westerdals.no',
        'password' => str_random(32),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Subject::class, function (Generator $faker) {
    return [
        'name' => str_random(20),
        'faculty_id' => $faker->randomElement(Faculty::KEYS)
    ];
});

$factory->define(Lecture::class, function (Generator $faker) {
    return [
        'access_code' => str_random(5),
        'ended_at' => null,
        'started_at' => Carbon::now(),
        'subject_id' => factory(Subject::class)->create()->id
    ];
});


$factory->define(Question::class, function (Generator $faker) {
    return [
        'answer' => $faker->paragraph(),
        'answered_at' => Carbon::now(),
        'description' => $faker->paragraph(),
        'lecture_id' => factory(Lecture::class)->create()->id
    ];
});
