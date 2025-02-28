<?php
namespace Database\Factories\BusinessFile\OpinionPolls;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BusinessFile\OpinionPolls\OpinionPoll;
use App\Models\BusinessFile\OpinionPolls\OpinionPollOption;

class OpinionPollOptionFactory extends Factory
{
    protected $model = OpinionPollOption::class;
    public function definition()
    {
        return [
            'opinion_poll_id' => OpinionPoll::inRandomOrder()->value('id'),
            'option'          => $this->faker->sentence(3),
            'votes'           => 0,
        ];
    }
}
