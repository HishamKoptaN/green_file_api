<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create(
            'notification_recipients',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'notification_recipients',
        );
    }
};
