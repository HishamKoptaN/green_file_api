<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'plans',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedDouble('amount');
                $table->unsignedDouble('user_amount_per_referal');
                $table->unsignedDouble('refered_amount');
                $table->unsignedDouble('amount_after_count');
                $table->unsignedDouble('count')->default(10);
                $table->unsignedDouble('transfer_commission', 5, 2)->default(0);
                $table->unsignedDouble('discount')->nullable();
                $table->string('discount_type')->comment('fixed - porcentage')->nullable();
                $table->unsignedDouble('daily_transfer_count')->default(10);
                $table->unsignedDouble('monthly_transfer_count')->default(10);
                $table->unsignedDouble('max_transfer_count')->default(10);
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists(
            'plans',
        );
    }
};
