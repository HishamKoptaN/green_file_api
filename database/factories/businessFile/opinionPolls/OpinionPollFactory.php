<?php

namespace Database\Factories\BusinessFile\OpinionPolls;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BusinessFile\OpinionPolls\OpinionPoll;
use App\Models\User\Company;

class OpinionPollFactory extends Factory
{
    protected $model = OpinionPoll::class;
    public function definition()
    {
        return [
            'company_id'  => Company::inRandomOrder()->first()->id ?? 1,
            'content' => $this->faker->paragraph,
        ];
    }
}
