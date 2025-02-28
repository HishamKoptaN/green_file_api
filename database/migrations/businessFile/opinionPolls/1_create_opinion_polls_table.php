<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(
            'opinion_polls',
            function (
                Blueprint $table,
            ) {
                $table->id();
                $table->boolean('status')->default(true)->comment('true-false');
                $table->text('content')->nullable();
                $table->date('end_date')->nullable();
                $table->foreignId("company_id")->constrained('companies')->cascadeOnDelete();
                $table->timestamps();
            },
        );
    }
    public function down()
    {
        Schema::dropIfExists(
            'opinion_polls',
        );
    }
};
