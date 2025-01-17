<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalutationToRotariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rotarians', function (Blueprint $table) {
            $table->enum('contact_salutation', ['Dr.', 'Ms.', 'Miss', 'Mr.', 'Mrs.'])->default('Dr.');
            $table->dropColumn(['work_phone', 'fax']);
            $table->renameColumn('home_phone', 'phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rotarians', function (Blueprint $table) {
            //
        });
    }
}
