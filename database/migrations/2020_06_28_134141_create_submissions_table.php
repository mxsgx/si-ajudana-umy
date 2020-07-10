<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecturer_id');
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('category_id');
            $table->enum('status', [
                'unauthorized', 'authorized', 'approved', 'rejected',
            ])->default('unauthorized');
            $table->string('name')->index();
            $table->date('date_start')->nullable();
            $table->time('time_start')->nullable();
            $table->date('date_end')->nullable();
            $table->time('time_end')->nullable();
            $table->text('place')->nullable();
            $table->text('note')->nullable();
            $table->string('title')->nullable();
            $table->string('writer')->nullable();
            $table->string('schema')->nullable();
            $table->string('grant')->nullable();
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
        Schema::dropIfExists('submissions');
    }
}
