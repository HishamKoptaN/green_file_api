<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailVerificationLinkNotification extends Notification
{
    use Queueable;
    public $verificationLink;
    public function __construct(
        $verificationLink,
    ) {
        $this->verificationLink = $verificationLink;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email', $this->verificationLink)
            ->line('If you did not request this email, no further action is required.');
    }

    public function toArray($notifiable)
    {
        return [
            'verificationLink' => $this->verificationLink,
        ];
    }
}
