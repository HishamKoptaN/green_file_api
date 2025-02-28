<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'opinion_poll_options',
            function (
                Blueprint $table,
            ) {
                $table->id();
                $table->string('option');
                $table->foreignId("opinion_poll_id")->constrained('opinion_polls')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'opinion_poll_options',
        );
    }
};
