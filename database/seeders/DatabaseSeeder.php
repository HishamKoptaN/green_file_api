<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\location\CountriesSeeder;
use Database\Seeders\location\CitiesSeeder;
use Database\Seeders\power\RolesSeeder;
use Database\Seeders\user\UsersSeeder;
use Database\Seeders\power\UserRoleSeeder;
use Database\Seeders\social\post\PostSeeder;
use Database\Seeders\social\statuses\StatusSeeder;
use Database\Seeders\user\OpportunityLookingSeeder;
use Database\Seeders\user\CompanySeeder;
use Database\Seeders\job\SkillSeeder;
use Database\Seeders\job\JobSeeder;
use Database\Seeders\job\JobSkillSeeder;
use Database\Seeders\job\JobSpecializationSeeder;
use Database\Seeders\businessFile\opinionPolls\OpinionPollSeeder;
use Database\Seeders\businessFile\opinionPolls\OpinionPollOptionSeeder;
use Database\Seeders\businessFile\opinionPolls\OpinionPollResponseSeeder


;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                CountriesSeeder::class,
                CitiesSeeder::class,
                RolesSeeder::class,
                UsersSeeder::class,
                UserRoleSeeder::class,
                PostSeeder::class,
                StatusSeeder::class,
                OpportunityLookingSeeder::class,
                CompanySeeder::class,
                JobSpecializationSeeder::class,
                OpinionPollSeeder::class,
                OpinionPollOptionSeeder::class,
                OpinionPollResponseSeeder::class,
                SkillSeeder::class,
                JobSeeder::class,
                JobSkillSeeder::class,
            ],
        );
    }
}
