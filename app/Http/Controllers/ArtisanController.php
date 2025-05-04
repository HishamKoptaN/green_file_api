<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function optimize()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('optimize');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        Artisan::call('composer dump-autoload');

        return response()->json(['message' => 'تم تنفيذ الأوامر بنجاح']);
    }
}
