<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //$this->call(DepartmentsTableSeeder::class);
        //$this->call(Days_in_MonthsTableSeeder::class);
        $this->call(Custom_DatesTableSeeder::class);
        //$this->call(CustomDatesItemsSeeder::class);

        Model::reguard();
    }
}
