<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table(
            'opportunity_lookings',
            function (Blueprint $table) {
                $table->string('job_title')->nullable()->after('name');
            },
        );
    }
};
