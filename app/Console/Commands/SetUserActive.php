<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetUserActive extends Command
{
    protected $signature = 'user:set-active';
    protected $description = 'Set all users to active (false)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            User::query()->update(
                [
                    'status' => true,
                ],
            );
            $this->info(
                'All users set to active successfully.',
            );
        } catch (\Exception $e) {
            $this->error(
                'Error: ' . $e->getMessage(),
            );
        }
    }
}
