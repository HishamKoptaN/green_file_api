<?php

namespace Database\Factories\BusinessFile;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\Company;

class NewFactory extends Factory
{
    public function definition()
    {
        return [
            'company_id' => Company::inRandomOrder()->value('id') ?? 1,
            'content' => $this->faker->paragraph(),
        ];
    }
}
