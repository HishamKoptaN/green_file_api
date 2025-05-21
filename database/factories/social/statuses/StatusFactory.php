<?php

namespace Database\Factories\social\statuses;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Social\Status\Status;

class StatusFactory extends Factory
{
    protected $model = Status::class;
    public function definition()
    {
        $type = $this->faker->randomElement(
            [
                'text',
                'image',
                'video',
                'text_image',
                'text_video',
            ],
        );

        return [
            'user_id' => $this->faker->randomElement(
                [
                    1,
                    2,
                    3,
                    4,
                    5,
                ],
            ),
            'content' => in_array(
                $type,
                [
                    'text',
                    'text_image',
                    'text_video',
                ],
            ) ? $this->faker->paragraph : null,
            'image' => in_array(
                $type,
                [
                    'image',
                    'text_image',
                ],
            ) ? $this->faker->randomElement(
                [
                    'https://res.cloudinary.com/dqzu6ln2h/image/upload/v1741171124/samples/balloons.jpg',
                    'https://res.cloudinary.com/dqzu6ln2h/image/upload/v1741171126/samples/cup-on-a-table.jpg',
                ],
            ) : null,
            'video' => in_array(
                $type,
                [
                    'video',
                    'text_video',
                ],
            ) ? $this->faker->randomElement(
                [
                    'https://player.cloudinary.com/embed/?cloud_name=dqzu6ln2h&public_id=samples%2Fsea-turtle&profile=cld-default',
                    'https://player.cloudinary.com/embed/?cloud_name=dqzu6ln2h&public_id=samples%2Felephants&profile=cld-default',
                ],
            ) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
