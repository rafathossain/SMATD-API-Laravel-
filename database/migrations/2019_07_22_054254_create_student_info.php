<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('rfid');
            $table->text('name');
            $table->text('mobile');
            $table->text('roll');
            $table->text('class');
            $table->text('address');
            $table->text('father');
            $table->text('mother');
            $table->text('age');
            $table->text('bGroup');
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
        Schema::dropIfExists('student_info');
    }
}
