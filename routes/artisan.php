<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get(
    '/run-cache-commands',
    function () {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        Artisan::call('cache:clear');
        return "Cache commands executed successfully!";
    },
);
Route::get(
    '/migrate-fresh',
    function () {
        Artisan::call('migrate:fresh');
        return "Migration fresh command executed!";
    },
);
Route::get(
    '/migrate',
    function () {
        Artisan::call('migrate');
        return "Migration command executed!";
    },
);
Route::get(
    '/dump-autoload',
    function () {
        exec('composer dump-autoload');
        return "Composer dump-autoload command executed!";
    },
);
Route::get(
    '/db-test',
    function () {
        try {
            DB::connection()->getPdo();
            return "Connected successfully to the database!";
        } catch (\Exception $e) {
            return "Could not connect to the database. Error: " . $e->getMessage();
        }
    },
);
Route::get(
    '/run-seeder',
    function () {
        try {
            Artisan::call('db:seed');
            $output = Artisan::output();
            return response()->json([
                'status' => 'success',
                'message' => 'Seeding completed successfully!',
                'output' => $output
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seeding failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    },
);
Route::get(
    '/test',
    function () {
        return "test artisan";
    },
);
