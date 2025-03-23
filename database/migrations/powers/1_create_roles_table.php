<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $teams = config('permission.teams');
        $columnNames = config('permission.column_names');
        Schema::create(
            'roles',
            function (Blueprint $table) use ($teams, $columnNames) {
                $table->id();
                if ($teams) {
                    $table->unsignedBigInteger(
                        $columnNames['team_foreign_key'],
                    )->nullable();
                    $table->index(
                        $columnNames['team_foreign_key'],
                        'roles_team_foreign_key_index',
                    );
                }
                $table->string('name');
                $table->string('guard_name')->default(
                    'api',
                );
                $table->timestamps();

                if ($teams) {
                    $table->unique(
                        [
                            $columnNames['team_foreign_key'],
                            'name',
                            'guard_name',
                        ],
                    );
                } else {
                    $table->unique(
                        [
                            'name',
                            'guard_name',
                        ],
                    );
                }
            },
        );
    }
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
