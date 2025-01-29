<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->unsignedBigInteger('dentist_id')->nullable();
            $table->foreign('dentist_id')->references('id')->on('dentists')->onDelete('set null');
            $table->unsignedBigInteger('rotarian_id')->nullable();
            $table->foreign('rotarian_id')->references('id')->on('rotarians')->onDelete('set null');
            $table->dateTime('presentation_date')->nullable();
            $table->unsignedSmallInteger('registration_year');
            $table->unsignedSmallInteger('number_of_classes')->nullable();
            $table->unsignedInteger('number_of_students')->nullable();
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
        Schema::dropIfExists('presentations');
    }
}
