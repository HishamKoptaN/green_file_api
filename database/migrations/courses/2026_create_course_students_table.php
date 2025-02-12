<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'user_courses',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId("course_id")->constrained('courses')->cascadeOnDelete();
                $table->foreignId("student_id")->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('course_students');
    }
};
