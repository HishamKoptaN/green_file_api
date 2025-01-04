<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Currency;

class TransferSeeder extends Seeder
{
    public function run()
    {
        $senderCurrencyIds = Currency::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        if (empty($senderCurrencyIds) || empty($userIds)) {
            $this->command->info('Required users or currencies not found, seeder not executed.');
            return;
        }
        $image =  ['invoice1.png', 'invoice2.png', 'invoice3.png'];
        for ($i = 0; $i < 20; $i++) {
            $amount = $this->getRandomAmount();
            $rate = $this->getRandomRate();
            $netAmount = $amount - ($amount * $rate / 100);

            Transfer::create(
                [
                    'status' => $this->getRandomStatus(),
                    'amount' => $amount,
                    'net_amount' => $netAmount,
                    'rate' => $rate,
                    'message' => $this->getRandomMessage(),
                    'employee_id' => $this->getRandomUserId($userIds),
                    'user_id' => $this->getRandomUserId($userIds),
                    'sender_currency_id' => $this->getRandomCurrencyId($senderCurrencyIds),
                    'receiver_currency_id' => $this->getRandomCurrencyId($senderCurrencyIds),
                    'receiver_account' => 2411111,
                    'image' => "https://api.aquan.website/public/storage/transfers/" . $image[array_rand($image)],
                    'address' => $this->getRandomAddress(),
                ],
            );
        }
    }

    private function getRandomStatus()
    {
        $statuses = ['accepted', 'rejected', 'pending'];
        return $statuses[array_rand($statuses)];
    }

    private function getRandomAmount()
    {
        return rand(100, 10000);
    }

    private function getRandomRate()
    {
        return rand(1, 5);
    }

    private function getRandomMessage()
    {
        $messages = ['Payment for services', 'Transfer to account', 'Refund', 'Other'];
        return $messages[array_rand($messages)];
    }

    private function getRandomUserId($userIds)
    {
        return $userIds[array_rand($userIds)];
    }

    private function getRandomCurrencyId($currencyIds)
    {
        return $currencyIds[array_rand($currencyIds)];
    }

    private function getRandomImage($images)
    {
        return $images[array_rand($images)];
    }

    private function getRandomAddress()
    {
        $addresses = ['123 Main St', '456 Elm St', '789 Maple Ave'];
        return $addresses[array_rand($addresses)];
    }
}
