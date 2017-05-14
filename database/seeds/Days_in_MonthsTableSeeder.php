<?php

use Illuminate\Database\Seeder;

class Days_in_MonthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 32; $i++) { 
        	DB::table('days_in_months')->insert([
        	    'monthday' => $i,
        	]);
        }
    }
}
