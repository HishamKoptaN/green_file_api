<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'status_views',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('status_id')
                    ->constrained('statuses')
                    ->cascadeOnDelete();
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->timestamp('viewed_at')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'status_views',
        );
    }
};
