<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'job_applications',
            function (Blueprint $table) {
                $table->id();
                $table->string('message');
                $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
                $table->foreignId('job_seeker_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};
