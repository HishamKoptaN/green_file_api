<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'opinion_poll_responses',
            function (
                Blueprint $table,
            ) {
                $table->id();
                $table->foreignId("user_id")->constrained('users')->cascadeOnDelete();
                $table->foreignId("opinion_poll_option_id")->constrained('opinion_poll_options')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'opinion_poll_responses',
        );
    }
};
