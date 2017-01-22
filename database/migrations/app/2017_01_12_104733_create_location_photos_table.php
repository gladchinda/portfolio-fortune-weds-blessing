<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_photos', function (Blueprint $table) {
            $table->unsignedInteger('location');
            $table->string('photo');

            $table->unique(['location', 'photo']);

            $table->foreign('location')->references('id')->on('locations');
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

        Schema::table('location_photos', function ($table) {
            $table->dropForeign(['location']);
        });

        Schema::dropIfExists('location_photos');
    }
}
