<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'app_controls',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment(
                    'true-false',
                );
                $table->string(
                    'branch_name',
                );
                $table->string(
                    'message',
                );
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('app_control');
    }
};
