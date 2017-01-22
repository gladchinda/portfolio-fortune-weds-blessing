<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from');
            $table->enum('via', ['email', 'phone']);
            $table->string('recipient')->unique();
            $table->string('code')->unique();
            $table->string('fullname')->nullable();
            $table->datetime('created');
            $table->datetime('followed')->nullable();
            $table->enum('status', ['accepted', 'rejected'])->nullable();

            $table->foreign('from')->references('id')->on('accounts');
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

        Schema::table('invitations', function ($table) {
            $table->dropForeign(['from']);
        });
        
        Schema::dropIfExists('invitations');
    }
}
