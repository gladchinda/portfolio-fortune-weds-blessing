<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoupleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couple', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('which', ['bride', 'groom']);
            $table->string('photo');

            $table->string('firstname', 32);
            $table->string('lastname', 32);
            $table->string('middlename', 32)->nullable();
            $table->string('nickname', 32)->nullable();

            $table->date('birthday')->nullable();
            $table->string('occupation')->nullable();
            $table->text('bio');

            $table->string('community');
            $table->string('lga');
            $table->string('state');

            $table->string('father');
            $table->string('father_photo');

            $table->string('mother');
            $table->string('mother_photo');

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('couple');
    }
}
