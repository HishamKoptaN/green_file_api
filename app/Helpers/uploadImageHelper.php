<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class uploadImageHelper
{
    public static function uploadImage(
        Request $request,
        $user,
        $folder,
        $fieldName = 'image',
    ) {

        $image = $request->file(
            $fieldName,
        );
        $imageName = time() . '_' . $image->getClientOriginalName();
        //! المسار الكامل حيث سيتم تخزين الصورة
        $destinationFolder = "/home/u943485201/domains/aquan.website/public_html/aquan_api/public/{$folder}/{$user->id}/";
        //! التأكد من أن المجلد موجود، وإلا نقوم بإنشائه
        if (!File::exists(
            $destinationFolder,
        )) {
            File::makeDirectory(
                $destinationFolder,
                0777,
                true,
                true,
            );
        }
        try {
            //! نقل الصورة إلى المجلد المحدد
            $image->move($destinationFolder, $imageName);
            return env('APP_URL') . "/public/{$folder}/{$user->id}/" . $imageName;
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'فشل في نقل الصورة'],
                500,
            );
        }
    }
    public static function updateImage(
        Request $request,
        $user,
        $folder,
        $oldImageUrl,
        $fieldName = 'image',
    ) {
        try {
            $parsedUrl = parse_url($oldImageUrl, PHP_URL_PATH);
            $parsedUrl = str_replace('/public', '', $parsedUrl);
            $storageProjectPath = "/home/u943485201/domains/aquan.website/public_html/aquan_api/public";
            $oldImagePath = $storageProjectPath . $parsedUrl;
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            } else {
                Log::warning(
                    "لم يتم العثور على الصورة: " . $oldImagePath,
                );
            }
            return self::uploadImage(
                $request,
                $user,
                $folder,
                $fieldName,
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل في تحديث الصورة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
