<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return failureRes(
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
            return successRes(
                $user,
            );
        } catch (\Exception $e) {
            return failureRes(
                __('Failed to update user: ') . $e->getMessage(),
            );
        }
    }
}
