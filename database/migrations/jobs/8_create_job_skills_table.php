<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'tests',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
                $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('tests');
    }
};
