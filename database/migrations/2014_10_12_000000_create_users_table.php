<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->index()->unique();
            $table->string('password');
            $table->enum('role', [
                'admin', 'dean', 'head-of-program-study', 'lecturer',
            ])->default('admin');
            $table->unsignedBigInteger('lecturer_id')->nullable();
            $table->unsignedBigInteger('study_id')->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
