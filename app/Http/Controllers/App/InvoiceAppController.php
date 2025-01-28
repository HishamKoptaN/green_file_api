<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceAppController extends Controller
{
    public function getWithdrawsAndDeposits(Request $request)
    {
        $invoices = Invoice::where('type', 'withdraw')
            ->orWhere(function ($builder) {
                $builder->where('type', 'deposit');
            })
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'invoices' => $invoices,
            'user' => $request->user()
        ]);
    }
}
