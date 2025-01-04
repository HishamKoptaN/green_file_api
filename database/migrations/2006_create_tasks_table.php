<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'tasks',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->string('name');
                $table->text('description');
                $table->unsignedDouble('amount');
                $table->string('link');
                $table->string('image')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
