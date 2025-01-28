<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Balance;


class AddDepositsToBalance extends Command
{
    protected $signature = 'deposits:add-to-available-balance';
    protected $description = 'Add approved deposits to users balance after 24 hours';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $deposits = Deposit::where('status', 'accepted')
            ->where('is_added_to_balance', false)
            ->where('approved_at', '<', Carbon::now()->subMinutes(1))
            // subHours(24))
            ->get();
        foreach ($deposits as $deposit) {
            $user = User::find(
                $deposit->user_idm,
            );
            if ($user) {
                $balance = Balance::where(
                    'user_id',
                    $deposit->user_id,
                )->first();
                $user->save();
            }
            if ($balance) {
                $balance->available_balance += $deposit->amount;
                $balance->suspended_balance -= $deposit->amount;
                $balance->save();
            }
            $deposit->is_added_to_balance = true;
            $deposit->save();
        }

        $this->info('Deposits added to balance successfully!');
    }
}
