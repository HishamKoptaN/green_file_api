<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'occasions',
            function (Blueprint $table) {
                $table->id();
                $table->string('image')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->text('link')->nullable();
                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'occasions',
        );
    }
};
