<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'trainings',
            function (Blueprint $table) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->foreignId("company_id")->constrained('companies')->cascadeOnDelete();
                $table->string('image');
                $table->string('title');
                $table->text('description');
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'trainings',
        );
    }
};
