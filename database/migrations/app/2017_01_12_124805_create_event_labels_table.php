<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_labels', function (Blueprint $table) {
            $table->unsignedInteger('event');
            $table->unsignedInteger('label');
            $table->boolean('many')->default(false);

            $table->unique(['event', 'label']);

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

        Schema::table('event_labels', function ($table) {
            $table->dropForeign(['event']);
            $table->dropForeign(['label']);
        });

        Schema::dropIfExists('event_labels');
    }
}
