<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $image = [
            '1.png',
            '2.png',
            '3.png',
        ];
        $users = [
            [
                'status' => true,
                'online_offline' => 'online',
                'account_number' => 2411111,
                'first_name' => "manager",
                'last_name' => "manager",
                'password' => Hash::make("password"),
                'email' => "manager@example.com",
                'image' => "https://api.aquan.website/public/storage/users/" . $image[array_rand(
                    $image,

                )],
                'address' => "manager",
                'phone' => "+2126000000",
                'inactivate_end_at' => null,
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'online_offline' => 'online',
                'account_number' => 2421111,
                'first_name' => "Admin",
                'last_name' => "Admin",
                'password' => Hash::make("password"),
                'email' => "admin@example.com",
                'image' => "https://api.aquan.website/public/storage/users/" . $image[array_rand(
                    $image,

                )],
                'address' => "Admin",
                'phone' => "+2126000002",
                'inactivate_end_at' => null,
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'online_offline' => 'online',
                'account_number' => 2431111,
                'first_name' => "Employee",
                'last_name' => "Employee",
                'password' => Hash::make("password"),
                'email' => "employee@example.com",
                'image' => "https://api.aquan.website/public/storage/users/" . $image[array_rand(
                    $image,
                )],
                'address' => "Employee",
                'phone' => "+2126000003",
                'inactivate_end_at' => null,
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'online_offline' => 'online',
                'account_number' => 2441111,
                'first_name' => "User",
                'last_name' => "User",
                'password' => Hash::make(
                    "password",
                ),
                'email' => "user@example.com",
                'image' => "https://api.aquan.website/public/storage/users/" . $image[array_rand(
                    $image,
                )],
                'address' => "User",
                'phone' => "+2126000006",
                'inactivate_end_at' => null,
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'online_offline' => 'online',
                'account_number' => 2451111,
                'first_name' => "User2",
                'last_name' => "User2",
                'password' => Hash::make("password"),
                'email' => "user2@example.com",
                'image' => "https://api.aquan.website/public/storage/users/" . $image[array_rand(
                    $image,
                )],
                'address' => "Address User2",
                'phone' => "+2126000007",
                'inactivate_end_at' => null,
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'online_offline' => 'online',
                'account_number' => 2461111,
                'first_name' => "Hisham",
                'last_name' => "Mohamed",
                'password' => Hash::make("password"),
                'email' => "heshamkoptan@gmail.com",
                'image' => "https://api.aquan.website/public/storage/users/" . $image[array_rand(
                    $image,
                )],
                'address' => "Address User2",
                'phone' => "+2126000007",
                'inactivate_end_at' => null,
                'comment' => 'comment',
            ],
        ];
        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
