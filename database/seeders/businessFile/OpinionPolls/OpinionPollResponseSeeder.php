<?php

namespace Database\Seeders\BusinessFile\OpinionPolls;

use Illuminate\Database\Seeder;
use App\Models\BusinessFile\OpinionPolls\OpinionPollResponse;
use App\Models\BusinessFile\OpinionPolls\OpinionPollOption;

class OpinionPollResponseSeeder extends Seeder
{
    public function run()
    {
        $userIds = [1, 2, 3];
        foreach ($userIds as $userId) {
            $options = OpinionPollOption::inRandomOrder()->limit(3)->get();
            foreach ($options as $option) {
                OpinionPollResponse::factory()->create([
                    'user_id'            => $userId,
                    'opinion_poll_id'    => $option->opinion_poll_id,
                    'opinion_poll_option_id' => $option->id,
                ],
            );
            }
        }
    }
}
