<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'rates',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->foreignId('plan_id')->constrained('plans');
                $table->foreignId('from')->constrained('currencies');
                $table->foreignId('to')->constrained('currencies');
                $table->double('price', 8, 1);
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
