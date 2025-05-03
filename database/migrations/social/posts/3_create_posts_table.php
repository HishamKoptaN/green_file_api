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
                $table->text('image_url')->nullable();
                $table->text('video_url')->nullable();
                $table->foreignId('original_post_id')->nullable()->constrained('posts')->cascadeOnDelete()->index();
                $table->enum('type', ['social', 'news', 'company'])->default('social');
                $table->timestamp('publish_at')->nullable()->index();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
