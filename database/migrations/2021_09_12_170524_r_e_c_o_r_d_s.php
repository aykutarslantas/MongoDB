<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RECORDS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RECORDS', function (Blueprint $table) {
            $table->id();
            $table->integer('building_id');
            $table->double('coordinate_x');
            $table->double('coordinate_y');
            $table->string('created_at');
            $table->integer('floor_id');
            $table->string('mac');
            $table->timestamps();
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
