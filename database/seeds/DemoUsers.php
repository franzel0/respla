<?php

use Illuminate\Database\Seeder;
use App\User;

class DemoUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\User::class, 10)->make();
    }
}
