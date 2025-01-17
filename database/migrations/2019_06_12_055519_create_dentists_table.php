<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDentistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dentists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('clinic_name')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('contact_salutation', ['Dr.', 'Ms.', 'Miss', 'Mr.', 'Mrs.'])->default('Dr.');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->unsignedSmallInteger('postcards')->default(0);
            $table->string('dental_society_name')->nullable();
            $table->boolean('is_organizing_committee')->default(0);
            $table->boolean('is_lootbag_committee')->default(0);
            $table->boolean('is_pr_media_committee')->default(0);
            $table->boolean('is_sponsor_committee')->default(0);
            $table->boolean('is_telephoning_committee')->default(0);
            $table->boolean('add_clinic_to_dentistfind')->default(0);
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
        Schema::dropIfExists('dentists');
    }
}
