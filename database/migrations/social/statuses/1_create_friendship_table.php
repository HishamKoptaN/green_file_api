<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'friendships',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('friend_id')->constrained('users')->cascadeOnDelete();
                $table->enum('status', [
                    'pending',
                    'accepted',
                    'declined',
                    'blocked',
                ])->default('pending');
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'friendships',
        );
    }
};
