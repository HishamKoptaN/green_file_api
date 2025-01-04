<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'transfers',
            function (Blueprint $table) {
                $table->id();

                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->unsignedDouble('amount');
                $table->unsignedDouble('net_amount');
                $table->unsignedDouble('rate');
                $table->text("message")->default('null');
                $table->string("image")->default('null');
                $table->string("address")->default('null');
                $table->foreignId('employee_id')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

                $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
                $table->foreignId('sender_currency_id')
                    ->constrained('currencies')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
                $table->foreignId('receiver_currency_id')
                    ->constrained('currencies')

                    ->cascadeOnUpdate();
                $table->string("receiver_account");
                $table->timestamps();
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
