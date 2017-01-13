<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_services', function (Blueprint $table) {
            $table->unsignedInteger('event');
            $table->unsignedInteger('service');
            $table->unsignedInteger('provider');

            $table->unique(['event', 'service', 'provider']);

            $table->foreign('event')->references('id')->on('events');
            $table->foreign('service')->references('id')->on('services');
            $table->foreign('provider')->references('id')->on('service_providers');
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

        Schema::table('event_services', function ($table) {
            $table->dropForeign(['event']);
            $table->dropForeign(['service']);
            $table->dropForeign(['provider']);
        });

        Schema::dropIfExists('event_services');
    }
}
