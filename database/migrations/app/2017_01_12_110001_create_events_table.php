<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('location');
            $table->date('date');
            $table->time('start');
            $table->time('end')->nullable()->default(null);

            $table->foreign('location')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('events', function ($table) {
            $table->dropForeign(['location']);
        });

        Schema::dropIfExists('events');
    }
}
