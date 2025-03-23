<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'user_education_details',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('education_level');
                $table->string('institution');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->text('details')->nullable();
                $table->string('grade')->nullable();
                $table->foreignId("country_id")->constrained('countries')->cascadeOnDelete();
                $table->foreignId("city_id")->constrained('cities')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }


    public function down()
    {
        Schema::dropIfExists('user_education_details');
    }
};
