<?php

namespace Database\Seeders\BusinessFile\OpinionPolls;

use Illuminate\Database\Seeder;
use App\Models\BusinessFile\OpinionPolls\OpinionPollResponse;
use App\Models\BusinessFile\OpinionPolls\OpinionPollOption;
use App\Models\BusinessFile\OpinionPolls\OpinionPoll;

class OpinionPollResponseSeeder extends Seeder
{
    public function run()
    {
        $users = [1, 2, 3];
        $polls = OpinionPoll::all();
        foreach ($polls as $poll) {
            $options = OpinionPollOption::where(
                'opinion_poll_id',
                $poll->id,
            )->get();
            if ($options->count() < 3) {
                OpinionPollOption::factory(
                    3 - $options->count(),
                )->create(
                    [
                        'opinion_poll_id' => $poll->id,
                    ],
                );
                $options = OpinionPollOption::where(
                    'opinion_poll_id',
                    $poll->id,

                )->get();
            }
            foreach ($users as $userId) {
                $selectedOption = $options->random();
                OpinionPollResponse::factory()->create(
                    [
                        'opinion_poll_id' => $poll->id,
                        'opinion_poll_option_id' => $selectedOption->id,
                        'user_id' => $userId,
                    ],
                );
            }
        }
    }
}
