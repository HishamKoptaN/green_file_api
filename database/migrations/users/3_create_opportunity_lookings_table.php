<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'opportunity_lookings',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string("image")->nullable();
                $table->foreignId("country_id")->nullable()->constrained('countries')->cascadeOnDelete();
                $table->foreignId("city_id")->nullable()->constrained('cities')->cascadeOnDelete();
                // $table->foreignId("area_id")->constrained('areas')->cascadeOnDelete()->nullable();
                $table->string("address")->nullable();
                $table->date("birth_date")->nullable();
                $table->string("marital_status")->nullable();
                $table->string("gender")->nullable();
                $table->string("nationality")->nullable();
                $table->string("phone")->nullable();
                $table->text("comment")->nullable();
                $table->text("country_code")->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('opportunity_lookings');
    }
};
