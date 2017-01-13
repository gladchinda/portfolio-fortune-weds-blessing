<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeeEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_emails', function (Blueprint $table) {
            $table->unsignedInteger('who');
            $table->unsignedInteger('email')->unique();
            $table->boolean('verified')->default(false);

            $table->primary('who');

            $table->foreign('who')->references('id')->on('attendees');
            $table->foreign('email')->references('id')->on('emails');
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

        Schema::table('attendee_emails', function ($table) {
            $table->dropForeign(['who']);
            $table->dropForeign(['email']);
        });

        Schema::dropIfExists('attendee_emails');
    }
}
