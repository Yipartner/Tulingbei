<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('stunum');
            $table->string('school_type');
            $table->string('school');
            $table->string('mobile')->unique();
            $table->string('faculty');
            $table->string('major');
            $table->string('sex');
            $table->string('email');
            $table->string('grade');
            $table->string('class');
            $table->string('lanqiaobei');
            $table->string('language');
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
        Schema::dropIfExists('students');
    }
}
