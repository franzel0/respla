<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->boolean('nj');
            $table->boolean('hk');
            $table->boolean('rm');
            $table->boolean('gd');
            $table->boolean('kf');
            $table->boolean('os');
            $table->boolean('om');
            $table->boolean('ta');
            $table->boolean('ch');
            $table->boolean('ps');
            $table->boolean('pm');
            $table->boolean('fl');
            $table->boolean('af');
            $table->boolean('mh');
            $table->boolean('te');
            $table->boolean('rt');
            $table->boolean('ah');
            $table->boolean('bb');
            $table->boolean('ha');
            $table->boolean('w1');
            $table->boolean('w2');
            $table->boolean('sy');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
