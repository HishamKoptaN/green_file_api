<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'notifications',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('public')->default(false);
                $table->string('message');
                $table->timestamps();
            },
        );
        Schema::create(
            'notification_user',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('notification_id')->constrained('notifications')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }


    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_user');
    }
};
