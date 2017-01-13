<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->unsignedInteger('type');
            $table->unsignedInteger('email');
            $table->datetime('created');
            $table->datetime('expires');

            $table->foreign('type')->references('id')->on('token_types');
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

        Schema::table('email_tokens', function ($table) {
            $table->dropForeign(['type']);
            $table->dropForeign(['email']);
        });

        Schema::dropIfExists('email_tokens');
    }
}
