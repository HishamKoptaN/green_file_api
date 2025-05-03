<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;

class SpecializationsApiController extends Controller
{
    // دالة لجلب جميع التخصصات
    public function get()
    {
        // جلب جميع البيانات من جدول التخصصات
        $specializations = Specialization::all();
        // إرجاع البيانات بصيغة JSON مع حالة النجاح
        return response()->json(
            $specializations,
        );
    }
    // دالة لجلب تخصص معين حسب الـ ID
    public function show($id)
    {
        // محاولة جلب التخصص باستخدام الـ ID
        $specialization = Specialization::find($id);

        // التحقق إذا كان التخصص موجودًا
        if ($specialization) {
            return response()->json([
                'status' => 'success',
                'data' => $specialization
            ]);
        }
        // إرجاع استجابة فشل إذا لم يتم العثور على التخصص
        return response()->json([
            'status' => 'fail',
            'message' => 'التخصص غير موجود'
        ], 404);
    }
}
