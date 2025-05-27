<?php

namespace App\Http\Controllers\Api\Global;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Relation;
class HideApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
        ]);
        $modelName = $this->resolveModel($request->type);
        $modelClass = Relation::getMorphedModel($modelName);
        if (! $modelClass) {
            return response()->json(['message' => 'نوع غير مدعوم'], 400);
        }
        $hideable = $modelClass::findOrFail($request->id);

        $hideable->hides()->create([
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'تم الإخفاء بنجاح']);
    }
    protected function resolveModel($type): string
    {
        return match ($type) {
            'post', 'status' => $type,
            default => abort(400, 'نوع غير مدعوم'),
        };
    }
}
