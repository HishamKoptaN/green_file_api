<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create(
            'notifications',
            function (
                Blueprint $table,
            ) {
                $table->id();
                $table->string(
                    'title',
                );
                $table->text(
                    'body',
                );
                $table->enum(
                    'type',
                    [
                        'global',
                        'private',
                    ],
                )->default('private');
                $table->string('image')->nullable();
                $table->json('data')->nullable();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'notifications',
        );
    }
};
