<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
class UserCreatedNotification extends Notification
{
     use Queueable;
    public User $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $link = $this->user->link;
        return (new MailMessage)
                    ->subject('Email Verification.')
                    ->greeting('Hello '. $this->user->name)
                    ->line('Thank you for signing up with our service. To complete your registration and gain access to all the features of our platform, we need you to verify your email address. Please click on the following link to verify your email address:')
                    ->action('Click to verify', url($link))
                    ->line('If you did not create an account with our service, you can safely ignore this email.')
                    ->line('Thank you,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
