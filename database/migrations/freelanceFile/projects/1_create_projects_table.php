<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'projects',
            function (Blueprint $table) {
                $table->id();
                $table->string('image');
                $table->string('name');
                $table->text('description');
                $table->foreignId("user_id")->constrained('users')->cascadeOnDelete();
                $table->foreignId("specialization_id")->constrained('specializations')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'projects',
        );
    }
};
