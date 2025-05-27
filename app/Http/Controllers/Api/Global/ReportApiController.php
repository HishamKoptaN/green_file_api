<?php

namespace App\Http\Controllers\Api\Global;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Social\Post\Post;
use App\Models\Social\Status\Status;
use Illuminate\Database\Eloquent\Relations\Relation;
class ReportApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'reason' => 'nullable|string',
        ]);

        $modelName = $this->resolveModel($request->type);
        $modelClass = Relation::getMorphedModel($modelName);

        if (! $modelClass) {
            return response()->json(['message' => 'نوع غير مدعوم'], 400);
        }

        $reportable = $modelClass::findOrFail($request->id);

        $reportable->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return response()->json(['message' => 'تم الإبلاغ بنجاح']);
    }


    protected function resolveModel($type): string
    {
        return match ($type) {
            'post', 'status' => $type,
            default => abort(400, 'نوع غير مدعوم'),
        };
    }

}
