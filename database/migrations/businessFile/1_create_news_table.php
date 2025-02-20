<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'news',
            function (Blueprint $table) {
                $table->id();
                $table->text('content');
                $table->foreignId("company_id")->constrained('companies')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'news',
        );
    }
};
