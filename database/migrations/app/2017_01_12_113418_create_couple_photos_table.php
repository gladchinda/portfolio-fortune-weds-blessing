<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouplePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couple_photos', function (Blueprint $table) {
            $table->unsignedInteger('who');
            $table->string('photo');

            $table->unique(['who', 'photo']);

            $table->foreign('who')->references('id')->on('couple')->onDelete('cascade');
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

        Schema::table('couple_photos', function ($table) {
            $table->dropForeign(['who']);
        });

        Schema::dropIfExists('couple_photos');
    }
}
