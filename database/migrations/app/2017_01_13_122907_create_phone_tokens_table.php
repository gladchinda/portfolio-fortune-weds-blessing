<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhoneTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->unsignedInteger('type');
            $table->unsignedInteger('phone');
            $table->datetime('created');
            $table->datetime('expires');

            $table->foreign('type')->references('id')->on('token_types');
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

        Schema::table('phone_tokens', function ($table) {
            $table->dropForeign(['type']);
            $table->dropForeign(['phone']);
        });

        Schema::dropIfExists('phone_tokens');
    }
}
