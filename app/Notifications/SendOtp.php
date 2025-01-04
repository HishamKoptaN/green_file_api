<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtp extends Notification
{
    use Queueable;
    public $otp;
    public function __construct(
        $otp,
    ) {
        $this->otp = $otp;
    }

    public function via(
        $notifiable,
    ) {
        return [
            'mail',
        ];
    }

    public function toMail(
        $notifiable,
    ) {
        return (new MailMessage)
            ->subject(
                'Your OTP Code',
            )
            ->line(
                'Your OTP code is: ' . $this->otp,
            )
            ->line(
                'This code will expire in 5 minutes.',
            )
            // ->action('Verify Email', url('/'))
            ->line(
                'Thank you for using our application!',
            );
    }
    public function toArray(
        $notifiable,
    ) {
        return [
            'otp' => $this->otp,
        ];
    }
}
