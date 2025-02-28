<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsAppController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            default:
                return $this->failureRes();
        }
    }
    // public function handleSettings(Request $request)
    // {
    //     if ($request->setting_name) {

    //         $setting = Setting::where('name', $request->setting_name)->first();
    //         return [
    //             'status' => true,
    //             'content' => $setting?->content
    //         ];
    //     }

    //     $settings = Setting::get()->pluck('content', 'name')->toArray();

    //     return [
    //         'status' => true,
    //         'settings' => $settings
    //     ];
    // }
}
