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
            $table->string('name',50);
            $table->string('stunum',15);
            $table->string('school_type');
            $table->string('school');
            $table->string('mobile',20)->unique();
            $table->string('faculty',45);
            $table->string('major',50);
            $table->string('sex',10);
            $table->string('email',45);
            $table->string('grade',10);
            $table->string('class');
            $table->string('lanqiaobei');
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
