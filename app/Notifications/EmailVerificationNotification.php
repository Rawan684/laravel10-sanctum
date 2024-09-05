<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    public $user;
    private $code;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, $code)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting('Dear ' . $this->user->name)
            ->line('Your verification code is: ' . $this->code)
            ->line('Please enter this code to verify your email address.')
            ->action('Verify Email', route('api.confirm-email-vf-code', ['user' => $this->user->id]))
            ->line('Thank you for using our application!');
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
