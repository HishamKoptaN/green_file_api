<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('user_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // صاحب المشروع
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('user_projects');
    }
};
