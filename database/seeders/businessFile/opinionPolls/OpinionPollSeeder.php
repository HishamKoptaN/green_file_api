<?php

namespace Database\Seeders\BusinessFile\OpinionPolls;
use Illuminate\Database\Seeder;
use App\Models\BusinessFile\OpinionPolls\OpinionPoll;
class OpinionPollSeeder extends Seeder
{
    public function run()
    {
        OpinionPoll::factory()->count(25)->create();
    }
}
