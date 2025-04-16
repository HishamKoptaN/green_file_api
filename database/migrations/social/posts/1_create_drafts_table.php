<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'drafts',
            function (Blueprint $table) {
                $table->id();
                $table->text('content')->nullable();
                $table->text('image')->nullable();
                $table->text('video')->nullable();
                $table->text('pdf')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'drafts',
        );
    }
};
