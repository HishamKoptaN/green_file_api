<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\ServiceRequest;
use App\Models\Social\Post\Post;
use App\Models\User\User;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {

        ServiceRequest::factory()
            ->count(15)
            ->create()
            ->each(
                function ($serviceRequest) {
                    $user = User::inRandomOrder()->first();
                    Post::create(
                        [

                            'user_id' => $user->id,
                            'postable_id' => $serviceRequest->id,
                            'postable_type' => ServiceRequest::class,
                        ],
                    );
                },
            );
    }
}
