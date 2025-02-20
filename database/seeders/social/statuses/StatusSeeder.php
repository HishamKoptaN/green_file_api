<?php

namespace Database\Seeders\social\statuses;

use Illuminate\Database\Seeder;
use App\Models\Social\Status\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        Status::factory(15)->create();
    }
}
