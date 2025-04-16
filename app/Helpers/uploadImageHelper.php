<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class uploadImageHelper
{
    public static function uploadFile(
        Request $request,
        $user,
        $folder,
        $fieldName = 'file'
    ) {
        $file = $request->file(
            $fieldName,
        );
        if (!$file) {
            throw new \Exception("لم يتم العثور على الملف.");
        }
        // الحصول على اسم الملف وامتداده
        $fileName = time() . '_' . $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        // تحديد المجلد بناءً على نوع الملف
        switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
                $folder = 'images';
                break;

            case 'pdf':
                $folder = 'pdfs';
                break;

            case 'mp4':
            case 'avi':
            case 'mkv':
                $folder = 'videos';
                break;

            case 'mp3':
            case 'wav':
            case 'ogg':
                $folder = 'audios';
                break;

            default:
                throw new \Exception("نوع الملف غير مدعوم.");
        }

        // المسار الكامل حيث سيتم تخزين الملف
        $destinationFolder = "/home/u943485201/domains/aquan.website/public_html/aquan_api/public/{$folder}/{$user->id}/";

        // التأكد من أن المجلد موجود، وإذا لم يكن موجودًا نقوم بإنشائه
        if (!File::exists($destinationFolder)) {
            File::makeDirectory($destinationFolder, 0777, true, true);
        }

        // إضافة تسجيل للتحقق من المسار
        Log::info("Destination folder: " . $destinationFolder);

        try {
            // نقل الملف إلى المجلد المحدد
            $file->move($destinationFolder, $fileName);
            return env('APP_URL') . "/public/{$folder}/{$user->id}/" . $fileName;
        } catch (\Exception $e) {
            Log::error("Error uploading file: " . $e->getMessage());
            throw new \Exception("فشل في رفع الملف: " . $e->getMessage());
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
            return self::uploadFile(
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
