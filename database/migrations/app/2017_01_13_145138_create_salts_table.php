<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaltsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salts', function (Blueprint $table) {
            $table->unsignedInteger('account');
            $table->string('salt');

            $table->primary('account');

            $table->foreign('account')->references('id')->on('accounts');
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

        Schema::table('salts', function ($table) {
            $table->dropForeign(['account']);
        });

        Schema::dropIfExists('salts');
    }
}
