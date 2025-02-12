<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'jobs',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->string('title');
                $table->text('description');
                $table->string('job_type');
                $table->decimal('min salary', 10, 2);
                $table->decimal('max_salary', 10, 2);
                $table->string('currency');
                $table->integer('applicants_count')->default(0);
                $table->foreignId("country_id")->nullable()->constrained('countries')->cascadeOnDelete();
                $table->foreignId("city_id")->nullable()->constrained('cities')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
