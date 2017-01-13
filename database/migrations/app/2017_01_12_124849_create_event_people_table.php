<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_people', function (Blueprint $table) {
            $table->unsignedInteger('who');
            $table->unsignedInteger('event');
            $table->unsignedInteger('label');

            $table->unique(['who', 'event', 'label']);

            $table->foreign('who')->references('id')->on('people');
            $table->foreign('event')->references('id')->on('events');
            $table->foreign('label')->references('id')->on('labels');
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

        Schema::table('event_people', function ($table) {
            $table->dropForeign(['who']);
            $table->dropForeign(['event']);
            $table->dropForeign(['label']);
        });

        Schema::dropIfExists('event_people');
    }
}
