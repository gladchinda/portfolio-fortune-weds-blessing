<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passwords', function (Blueprint $table) {
            $table->unsignedInteger('account');
            $table->string('salt');
            $table->string('hash');
            $table->datetime('created');

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

        Schema::table('passwords', function ($table) {
            $table->dropForeign(['account']);
        });

        Schema::dropIfExists('passwords');
    }
}
