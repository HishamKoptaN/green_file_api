<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create(
            'shared_posts',
            function (Blueprint $table) {
                $table->id();
                $table->text('content')->nullable();
                $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'post_shares',
        );
    }
};
