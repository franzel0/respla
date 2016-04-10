<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'name' => 'Chirurgie',
            'company_id' => 1,
            'active' => 1,
        ]);
        DB::table('departments')->insert([
            'name' => 'Innere Medizin',
            'company_id' => 1,
            'active' => 1,
        ]);
        DB::table('departments')->insert([
            'name' => 'Anästhesie',
            'company_id' => 1,
            'active' => 1,
        ]);
    }
}
