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
                $table->string('job_title'); // اسم الوظيفة
                $table->date('start_date'); // بداية العمل
                $table->date('end_date')->nullable(); // نهاية العمل (يمكن أن تكون NULL إذا كان لا يزال يعمل)
                $table->string('job_nickname'); // اسم الوظيفة
                $table->string('address'); // اسم الشركة
                $table->text('description')->nullable(); // تفاصيل الوظيفة
                $table->timestamps();
            },
        );
    }


    public function down()
    {
        Schema::dropIfExists('user_work_experiences');
    }
};
