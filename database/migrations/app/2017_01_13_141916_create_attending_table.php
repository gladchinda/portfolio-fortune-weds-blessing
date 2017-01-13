<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attending', function (Blueprint $table) {
            $table->unsignedInteger('who');
            $table->unsignedInteger('event');

            $table->unique(['who', 'event']);

            $table->foreign('who')->references('id')->on('attendees');
            $table->foreign('event')->references('id')->on('events');
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

        Schema::table('attending', function ($table) {
            $table->dropForeign(['who']);
            $table->dropForeign(['event']);
        });

        Schema::dropIfExists('attending');
    }
}
