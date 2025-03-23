<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'lessons',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->string('title');
                $table->text('content');
                $table->timestamps();
                $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
