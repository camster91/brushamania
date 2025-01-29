<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('child_parent_id');
            $table->foreign('child_parent_id')->references('id')->on('child_parents')->onDelete('cascade');
            $table->string('firstname');
            $table->string('lastname');
            $table->unsignedSmallInteger('age')->nullable();
            $table->string('grade')->nullable();
            $table->string('school')->nullable();
            $table->string('school_address')->nullable();
            $table->string('teacher_name')->nullable();
            $table->string('school_phone')->nullable();
            $table->boolean('give_consent_name')->nullable();
            $table->boolean('give_consent_picture')->nullable();
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
        Schema::dropIfExists('children');
    }
}
