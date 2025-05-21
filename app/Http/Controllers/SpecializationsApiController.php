<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\global\Specialization;

class SpecializationsApiController extends Controller
{
    public function get()
    {
        $specializations = Specialization::all();
        return response()->json(
            $specializations,
        );
    }
    public function show(
        $id,
    ) {
        $specialization = Specialization::find(
            $id,
        );
        if ($specialization) {
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $specialization
                ],
            );
        }
        return response()->json(
            [
                'status' => 'fail',
                'message' => 'التخصص غير موجود'
            ],
            404,
        );
    }
}
