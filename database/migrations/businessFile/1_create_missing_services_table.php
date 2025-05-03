<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'missing_services',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId("user_id")->constrained('users')->cascadeOnDelete();
                $table->string('title');
                $table->foreignId("specialization_id")->constrained('specializations')->cascadeOnDelete();
                $table->text('details');
                $table->timestamps();
            },
        );
    }

    public function down()
    {
        Schema::dropIfExists('missing_services');
    }
};
