<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{

    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المنشور مطلوب.',
            'content.required' => 'يجب إدخال محتوى المنشور.',
            'content.min' => 'يجب أن يكون محتوى المنشور على الأقل 10 أحرف.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
        ];
    }
}
