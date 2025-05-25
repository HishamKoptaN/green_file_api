<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'hidden_statuses',
            function (
                Blueprint $table,
            ) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('status_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'hidden_statuses',
        );
    }
};
