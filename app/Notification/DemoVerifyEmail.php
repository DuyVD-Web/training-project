<?php

namespace App\Notification;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemoVerifyEmail extends Notification
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('demo.verification.verify', ['token' => $this->token]);

        return (new MailMessage)->subject('Verify your email address')->line('Click the button below to verify your email address.')
            ->action('Verify email address.', $url)
            ->line('If you did not create an account, no further action is required.');
    }
}
