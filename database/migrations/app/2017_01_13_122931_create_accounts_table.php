<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->unsignedInteger('email')->unique();
            $table->string('fullname')->nullable();
            $table->boolean('creator')->default(false);
            $table->datetime('created');
            $table->datetime('activated')->nullable();
            $table->datetime('disabled')->nullable();

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

        Schema::table('accounts', function ($table) {
            $table->dropForeign(['email']);
        });

        Schema::dropIfExists('accounts');
    }
}
