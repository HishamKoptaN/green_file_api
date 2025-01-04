<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetUserInactive extends Command
{
    protected $signature = 'user:set-inactive';
    protected $description = 'Set all users to inactive (false)';

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
                'All users set to inactive successfully.',
            );
        } catch (\Exception $e) {
            $this->error(
                'Error: ' . $e->getMessage(),
            );
        }
    }
}
