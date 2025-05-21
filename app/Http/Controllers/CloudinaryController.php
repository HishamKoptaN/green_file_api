<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryController extends Controller
{
    public function upload(Request $request)
    {
        $result = Cloudinary::upload($request->file('image')->getRealPath());
        $uploadedFileUrl = $result->getSecurePath();
        $publicId = $result->getPublicId();
        return back()->with('success', 'Product added successfully with image');
    }
}
