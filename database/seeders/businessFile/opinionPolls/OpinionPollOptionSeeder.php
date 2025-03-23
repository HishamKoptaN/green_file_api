<?php

namespace Database\Seeders\BusinessFile\OpinionPolls;

use Illuminate\Database\Seeder;
use App\Models\BusinessFile\OpinionPolls\OpinionPoll;
use App\Models\BusinessFile\OpinionPolls\OpinionPollOption;

class OpinionPollOptionSeeder extends Seeder
{
    public function run()
    {
        $polls = OpinionPoll::all();
        foreach ($polls as $poll) {
            OpinionPollOption::create(
                [
                    'opinion_poll_id' => $poll->id,
                    'option' => 'الخيار الأول',
                ],
            );
            OpinionPollOption::create(
                [
                    'opinion_poll_id' => $poll->id,
                    'option' => 'الخيار الثاني',
                ],
            );
            OpinionPollOption::create(
                [
                    'opinion_poll_id' => $poll->id,
                    'option' => 'الخيار الثالث',
                ],
            );
        }
    }
}
