<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->string('firebase_uid')->unique()->nullable();
                $table->string('email')->unique();
                $table->string('password')->nullable();
                $table->string('account_number')->unique();
                $table->string('online_offline')->default('online')->comment('online - offline');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string("image")->nullable();
                $table->string("address")->nullable();
                $table->string("phone")->nullable();
                $table->text("comment")->nullable();
                $table->text("country_code")->nullable();
                $table->timestamp("verified_at")->nullable();
                $table->timestamp("inactivate_end_at")->nullable();
                $table->timestamps();
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
