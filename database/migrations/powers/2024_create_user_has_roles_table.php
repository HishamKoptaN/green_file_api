<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_has_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->morphs('model'); // هذا يضيف عمود model_id و model_type
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_has_roles');
    }
};
