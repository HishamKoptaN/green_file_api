<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'currencies',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->string('name');
                $table->string('name_code');
                $table->text('comment');
                $table->timestamps();
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
