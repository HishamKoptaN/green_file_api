<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'posts',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('content')->nullable();
                $table->string('image_url')->nullable();
                $table->string('video_url')->nullable();
                $table->unsignedBigInteger('original_post_id')->nullable();
                $table->foreign('original_post_id')->references('id')->on('posts')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
