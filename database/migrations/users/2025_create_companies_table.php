<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'companies',
            function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('phone');
                $table->string('image');
                $table->foreignId("country_id")->constrained('countries')->cascadeOnDelete();
                $table->foreignId("city_id")->constrained('cities')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }


    public function down()
    {
        Schema::dropIfExists(
            'companies',
        );
    }
};
