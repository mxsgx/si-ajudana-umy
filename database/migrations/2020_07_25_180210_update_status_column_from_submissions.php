<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnFromSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `submissions` CHANGE `status` `status` ENUM('unauthorized', 'authorized', 'authorized-co-dean', 'revision-co-dean', 'approved-co-dean', 'rejected-co-dean', 'approved', 'rejected') DEFAULT 'unauthorized';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `submissions` CHANGE `status` `status` ENUM('unauthorized', 'authorized', 'approved', 'rejected') DEFAULT 'unauthorized';");
    }
}
