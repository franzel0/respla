<?php

use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department_id=1;

        DB::table('positions')->insert([
            'name' => 'Chefarzt',
            'department_id' => $department_id,
            'priority' => 1,
            'active' => 1,
        ]);
        DB::table('positions')->insert([
            'name' => 'Oberarzt',
            'department_id' => $department_id,
            'priority' => 2,
            'active' => 1,
        ]);
        DB::table('positions')->insert([
            'name' => 'Assistenzarzt',
            'department_id' => $department_id,
            'priority' => 3,
            'active' => 1,
        ]);
        DB::table('positions')->insert([
            'name' => 'PJ',
            'department_id' => $department_id,
            'priority' => 4,
            'active' => 1,
        ]);
        
    }
}
