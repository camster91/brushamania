<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStudentRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            // $table->enum('role', ['admin', 'dentist', 'school', 'rotarian', 'parent', 'student'])->change();
            DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('admin', 'dentist', 'school', 'rotarian', 'parent', 'student') NOT NULL DEFAULT 'admin'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
