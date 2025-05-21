<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Log;

class uploadImageHelper
{
    public static function uploadFile(Request $request, $user, $folder, $fieldName = 'file')
    {
        $file = $request->file($fieldName);

        if (!$file) {
            throw new Exception("لم يتم العثور على الملف.");
        }

        try {
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();

            $resourceType = 'auto';
            $uploadOptions = [
                'folder' => "{$folder}/{$user->id}",
                'public_id' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'content_disposition' => 'inline',
            ];

            if (str_starts_with($mimeType, 'audio/') || str_starts_with($mimeType, 'video/')) {
                $resourceType = 'video';
            } elseif (str_starts_with($mimeType, 'image/')) {
                $resourceType = 'image';
            } elseif ($extension === 'pdf' || $mimeType === 'application/pdf') {
                $resourceType = 'raw';
            } else {
                $resourceType = 'raw';
            }

            $uploadOptions['resource_type'] = $resourceType;

            $uploaded = Cloudinary::upload($file->getRealPath(), $uploadOptions);

            return $uploaded->getSecurePath();

        } catch (Exception $e) {
            Log::error("Error uploading to Cloudinary: " . $e->getMessage());
            throw new Exception("فشل في رفع الملف إلى Cloudinary: " . $e->getMessage());
        }
    }
}
