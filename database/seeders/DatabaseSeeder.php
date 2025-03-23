<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\location\CountriesSeeder;
use Database\Seeders\location\CitiesSeeder;
use Database\Seeders\power\RolesSeeder;
use Database\Seeders\user\UsersSeeder;
use Database\Seeders\power\UserRoleSeeder;
use Database\Seeders\social\post\PostSeeder;
use Database\Seeders\social\status\StatusSeeder;
use Database\Seeders\user\OpportunityLookingSeeder;
use Database\Seeders\user\CompanySeeder;
use Database\Seeders\job\SkillSeeder;
use Database\Seeders\job\JobSeeder;
use Database\Seeders\job\JobSkillSeeder;
use Database\Seeders\businessFile\opinionPolls\OpinionPollSeeder;
use Database\Seeders\businessFile\opinionPolls\OpinionPollOptionSeeder;
use Database\Seeders\businessFile\opinionPolls\OpinionPollResponseSeeder;
use Database\Seeders\businessFile\NewsSeeder;
use Database\Seeders\businessFile\ServiceSeeder;
use Database\Seeders\businessFile\TrainingSeeder;
use Database\Seeders\freelanceFile\ProjectSeeder;
use Database\Seeders\job\JobApplicationSeeder;
use Database\Seeders\job\SpecializationSeeder;
use Database\Seeders\social\post\CmntSeeder;
use Database\Seeders\social\FollowerSeeder;
use Database\Seeders\social\FriendSeeder;
use Database\Seeders\social\LikeSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                //! location
                CountriesSeeder::class,
                CitiesSeeder::class,
                // !Users
                UsersSeeder::class,
                OpportunityLookingSeeder::class,
                CompanySeeder::class,
                //! power
                RolesSeeder::class,
                UserRoleSeeder::class,
                //! social
                PostSeeder::class,
                CmntSeeder::class,
                StatusSeeder::class,
                LikeSeeder::class,
                FollowerSeeder::class,
                FriendSeeder::class,

                //! businessFile
                OpinionPollSeeder::class,
                OpinionPollOptionSeeder::class,
                OpinionPollResponseSeeder::class,
                NewsSeeder::class,
                ServiceSeeder::class,
                TrainingSeeder::class,
                //! Course
                // CourseRatingSeeder::class,
                // CourseSeeder::class,
                // LessonSeeder::class,
                //! Job
                SpecializationSeeder::class,
                ProjectSeeder::class,
                SkillSeeder::class,
                JobSeeder::class,
                JobSkillSeeder::class,
                JobApplicationSeeder::class,
            ],
        );
    }
}
