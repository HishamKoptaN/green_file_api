<?php

namespace Database\Factories\BusinessFile\opinionPolls;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BusinessFile\OpinionPolls\OpinionPollOption;

class OpinionPollResponseFactory extends Factory

{

    public function definition()
    {
        return [
            'opinion_poll_option_id' => OpinionPollOption::inRandomOrder()->value('id'),
            'votes'  => 0,
        ];
    }
}
