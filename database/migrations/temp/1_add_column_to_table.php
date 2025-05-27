<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('social_posts', function (Blueprint $table) {
            $table->String('thumbnail_url')->nullable()->after('video');
        });
    }
};
