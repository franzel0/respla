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
use Faker\Factory as Faker;
use Carbon\Carbon;

$factory->define(App\User::class, function ( $faker) {
    //localized from: https://laracasts.com/discuss/channels/laravel/how-to-localize-the-modelfactory
    $faker = Faker::create('de_DE');
    $firstName = (rand(0,1)) ? $faker->firstNameFemale : $faker->firstNameMale;
    $loginName = $firstName.rand(0,1000);
    return [
        'name' => $loginName,
        'firstname' =>$firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt('secret'),
        'active' => 1,
        'confirmed' => 1,
    ];
});

$factory->define(App\Event::class, function ($faker) {
    //localized from: https://laracasts.com/discuss/channels/laravel/how-to-localize-the-modelfactory
    $faker = Faker::create('de_DE');
    // get holidays aka custom_dates to avoid them
    $holidays = App\CustomDate::whereRaw('Year(date) = 2016')->get();
    //find a date not on weekend or holiday
    do{
        $date = new Carbon($faker->dateTimeBetween('first day of January 2016', 'last day of December 2016')->format('Y-m-d'));
    }while ($date->isWeekend() || $holidays->contains($date));
    return [
        'date' => $date->toDateString(),
        'entry_id' => rand(1,5),
        'approved' => rand(0,1),
        'comment' => (rand(0,4)<2) ? $faker->sentence(8) : "",
    ];
});
