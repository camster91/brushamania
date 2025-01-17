<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rotarians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->string('firstname');
            $table->string('lastname');
            $table->string('work_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('rotary_club');
            $table->boolean('is_organizing_committee')->default(0);
            $table->boolean('is_lootbag_committee')->default(0);
            $table->boolean('is_pr_media_committee')->default(0);
            $table->boolean('is_sponsor_committee')->default(0);
            $table->boolean('is_telephoning_committee')->default(0);
            $table->unsignedSmallInteger('last_registration_year');
            $table->string('url_slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rotarians');
    }
}
