<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ApiResponseTrait
{
    public function successResponse($data = null, $status = 200)
    {
        return response()->json(
            $data,
            $status,
        );
    }
    public function failureResponse($errors = null, $status = 500)
    {
        if (is_array($errors)) {
            $errors = implode(', ', $errors);
        }
        return response()->json([
            'error' => $errors,
        ], $status);
    }
}
