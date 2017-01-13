<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_phones', function (Blueprint $table) {
            $table->unsignedInteger('who');
            $table->unsignedInteger('phone')->unique();
            $table->boolean('verified')->default(false);

            $table->primary('who');

            $table->foreign('who')->references('id')->on('attendees');
            $table->foreign('phone')->references('id')->on('phones');
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

        Schema::table('attendee_phones', function ($table) {
            $table->dropForeign(['who']);
            $table->dropForeign(['phone']);
        });

        Schema::dropIfExists('attendee_phones');
    }
}
