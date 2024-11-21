<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeEmailNotification extends Notification
{
    use Queueable;

    private $token;
    private $newEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $newEmail)
    {
        $this->token = $token;
        $this->newEmail = $newEmail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('user.info.verifyChangeEmail', ['token' => $this->token]);

        return (new MailMessage)
            ->subject('Verify Email Change')
            ->line('You have requested to change your email address.')
            ->line("New email address: {$this->newEmail}")
            ->action('Verify Email Change', $url)
            ->line('This link will expire in an hour.')
            ->line('If you did not request this change, no further action is required.');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
