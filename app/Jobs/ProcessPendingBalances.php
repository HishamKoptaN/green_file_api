<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\PendingBalance;

class ProcessPendingBalances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct() {}
    public function handle(): void
    {
        $pendingBalances = PendingBalance::where('created_at', '<', now()->subHours(24))->get();

        foreach ($pendingBalances as $pendingBalance) {
            $user = User::find($pendingBalance->user_id);
            // إضافة الرصيد المعلق إلى الءرصيد الفعلي
            $user->balance += $pendingBalance->amount;
            $user->save();

            // حذف الرصيد المعلق بعد إضافته إلى الرصيد الفعلي
            $pendingBalance->delete();
        }
    }
}
