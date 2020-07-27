<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRoleColumnFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM('admin', 'dean', 'head-of-program-study', 'lecturer', 'co-dean-1', 'co-dean-2') DEFAULT 'admin';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM('admin', 'dean', 'head-of-program-study', 'lecturer') DEFAULT 'admin';");
    }
}
