<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('pending')->comment(
                'pending-accepted-rejected',
            );
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->default('1')->constrained('users')->cascadeOnDelete();
            $table->unsignedDouble('amount');
            $table->foreignId('method')->constrained('currencies')->cascadeOnDelete();
            $table->string('image')->default('Null');
            $table->string('comment')->default('Null');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
