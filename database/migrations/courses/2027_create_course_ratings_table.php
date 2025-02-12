<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'course_ratings',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId("student_id")->constrained('users')->cascadeOnDelete();
                $table->foreignId("course_id")->constrained('courses')->cascadeOnDelete();
                $table->float('rating');
                $table->text('review')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('course_ratings');
    }
};
