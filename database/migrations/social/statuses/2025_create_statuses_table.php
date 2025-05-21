<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'statuses',
            function (
                Blueprint $table,
            ) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('image')->nullable();
                $table->text('content')->nullable();
                $table->text('font_size')->nullable();
                $table->text('font_color')->nullable();
                $table->text('font_family')->nullable();
                $table->string('video')->nullable();
                $table->string('time')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'statuses',
        );
    }
};
