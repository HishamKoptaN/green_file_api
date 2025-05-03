<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('msgs', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('video');
        });
    }
};
