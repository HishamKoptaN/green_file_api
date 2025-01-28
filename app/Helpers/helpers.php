<?php
if (!function_exists('uploadImage')) {
    /**
     * To upload image to the specified directory and return the image URL.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $directory
     * @return string
     */
    function uploadImage(
        $image,
        $directory,
    ) {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/' . $directory), $imageName);
        return asset('public/' . 'storage/' . $directory . '/' . $imageName);
    }
}
if (!function_exists('updateImage')) {
    /**
     * Update an existing image by replacing it with a new one in the specified directory.
     * Deletes the old image if it exists, only after the new image is uploaded successfully.
     *
     * @param \Illuminate\Http\UploadedFile $newImage
     * @param string $directory
     * @param string|null $oldImagePath
     * @return string|null
     */
    function updateImage($newImage, $directory, $oldImagePath = null)
    {
        $imageName = time() . '.' . $newImage->getClientOriginalExtension();
        $newImagePath = public_path('storage/' . $directory . '/' . $imageName);
        if ($newImage->move(public_path('storage/' . $directory), $imageName)) {
            if ($oldImagePath) {
                $oldImageName = basename(parse_url($oldImagePath, PHP_URL_PATH));
                $oldImagePath = public_path('storage/' . $directory . '/' . $oldImageName);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            return asset('storage/' . $directory . '/' . $imageName);
        }
        return null;
    }
}
