<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'company_posts',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId("company_id")->constrained('companies')->cascadeOnDelete();
                $table->text('content')->nullable();
                $table->string('image_url')->nullable();
                $table->string('video_url')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'company_posts',
        );
    }
};
