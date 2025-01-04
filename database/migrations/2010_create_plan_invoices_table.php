<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'plan_invoices',
            function (Blueprint $table) {
                $table->id();
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->string('image')->default('Null');
                $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
                $table->unsignedDouble('amount');
                $table->string('comment')->default('Null');
                $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('plan_invoices');
    }
};
