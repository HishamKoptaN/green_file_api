<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileAppController extends Controller
{
    public function handleRequest(
        Request $request,
    ) {
        switch ($request->method()) {
            case 'GET':
                return $this->get(
                    $request,
                );
            case 'POST':
                return $this->edit(
                    $request,
                );
            case 'PATCH':
                return $this->updateUserProfile(
                    $request,
                );
            default:
                return failureResponse(
                    'Invalid request method',
                );
        }
    }
    public function edit(
        Request $request,
    ) {
        try {
            $user = Auth::guard('sanctum')->user();
            $user->image =
                updateImage(
                    $request->file('image'),
                    'users',
                    $user->image
                );
            if ($request->filled('first_name')) {
                $user->first_name = $request->first_name;
            }
            if ($request->filled('last_name')) {
                $user->last_name = $request->last_name;
            }
            if ($request->filled('address')) {
                $user->address = $request->address;
            }
            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }
            $user->save();
            $user->load('balance');
            return successResponse(
                $user,
            );
        } catch (\Exception $e) {
            return failureResponse(
                __('Failed to update user: ') . $e->getMessage(),
            );
        }
    }
}
