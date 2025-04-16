<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'cvs',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('image');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('job_title');
                $table->string('email')->unique();
                $table->string('phone');
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->date('birthdate')->nullable();
                $table->enum(
                    'marital_status',
                    [
                        'single',
                        'married',
                        'divorced',
                    ],
                )->nullable();
                $table->enum(
                    'gender',
                    [
                        'male',
                        'female',
                    ],
                )->nullable();
                $table->string(
                    'nationality',
                )->nullable();
                $table->timestamps();
            },
        );

        Schema::create(
            'work_experiences',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
                $table->string('job_title');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->string('position');
                $table->string('address')->nullable();
                $table->text('details')->nullable();
                $table->timestamps();
            },
        );
        Schema::create(
            'educations',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
                $table->string('school');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->string('education_level');
                $table->text('details')->nullable();
                $table->string('grade')->nullable();
                $table->enum('institution_type', ['governmental', 'private'])->nullable();
                $table->string('city')->nullable();
                $table->timestamps();
            },
        );
        // Schema::create(
        //     'languages',
        //     function (Blueprint $table) {
        //         $table->id();
        //         $table->foreignId('profile_id')->constrained()->onDelete('cascade');
        //         $table->string('language');
        //         $table->unsignedTinyInteger('proficiency');
        //         $table->timestamps();
        //     },
        // );
        // Schema::create(
        //     'social_links',
        //     function (Blueprint $table) {
        //         $table->id();
        //         $table->foreignId('profile_id')->constrained()->onDelete('cascade');
        //         $table->string('platform');
        //         $table->string('url');
        //         $table->timestamps();
        //     },
        // );
    }

    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
