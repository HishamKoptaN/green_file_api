<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\User\OpportunityLookingResource;
use App\Models\User\OpportunityLooking;

class OpportunityLookingController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            if (!$query) {
                return failureRes([
                    'message' => 'يرجى إدخال قيمة للبحث',
                    'code' => 400
                ],
            );
            }
            $opportunityLookings = OpportunityLooking::where('first_name', 'LIKE', $query . '%')
                ->orWhere('last_name', 'LIKE', $query . '%')
                ->get();
            return successRes([
                'opportunity_lookings' => $opportunityLookings,
            ],
        );
        } catch (\Exception $e) {
            return failureRes([
                'message' => $e->getMessage(),
                'code' => 500
            ]);
        }
    }
}
