<?php

namespace Database\Factories\businessFile\opinionPolls;

use Illuminate\Database\Eloquent\Factories\Factory;

class OpinionPollResponseFactory extends Factory

{
    public function definition()
    {
        return [
            'opinion_poll_id' => null,
            'opinion_poll_option_id' => null,
            'user_id' => null,
        ];
    }
}
