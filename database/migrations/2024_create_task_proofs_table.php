<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'task_proofs',
            function (Blueprint $table) {
                $table->id();
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
                $table->string('image')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('task_proofs');
    }
};
