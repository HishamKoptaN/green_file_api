<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'user_work_experiences',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // ربط بالمستخدم
                $table->string('job_title');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->string('job_nickname');
                $table->string('address');
                $table->text('description')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('user_work_experiences');
    }
};
