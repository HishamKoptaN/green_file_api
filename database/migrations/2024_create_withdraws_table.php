<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'withdraws',
            function (Blueprint $table) {
                $table->id();
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('method')->constrained('currencies')->cascadeOnDelete();
                $table->string('receiving_account_number');
                $table->unsignedDouble('amount');
                $table->string('image')->default('Null');
                $table->string('comment')->default('Null');
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
