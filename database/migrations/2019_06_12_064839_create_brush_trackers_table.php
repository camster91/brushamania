<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrushTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brush_trackers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
            $table->boolean('morning_brush')->default(0);
            $table->boolean('morning_floss')->default(0);
            $table->boolean('lunchtime_brush')->default(0);
            $table->boolean('lunchtime_floss')->default(0);
            $table->boolean('night_brush')->default(0);
            $table->boolean('night_floss')->default(0);
            $table->boolean('bonus_brush')->default(0);
            $table->boolean('bonus_floss')->default(0);
            $table->date('brush_date');
            $table->unsignedSmallInteger('year');
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
        Schema::dropIfExists('brush_trackers');
    }
}
