<?php

namespace Database\Seeders\Social\Status;

use Illuminate\Database\Seeder;
use App\Models\Social\Status\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        Status::factory(15)->create();
    }
}
