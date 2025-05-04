<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class CloudnaryController extends Controller
{
    public function uploadImage(Request $request)
    {
        // تحقق من الصورة في المدخلات
        $request->validate([
            'image' => 'required|image|max:10240',  // تأكد من أن الصورة موجودة وصحيحة
        ]);

        // تحميل الصورة إلى Cloudinary
        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

        // إرجاع رابط الصورة المرفوعة في استجابة JSON
        return response()->json([
            'message' => 'Image uploaded successfully!',
            'image_url' => $uploadedFileUrl
        ], 200);
    }
}
