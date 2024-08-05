<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Illuminate\Notifications\Notification;

class RenewPasswordNotification extends Notification
{
    use Queueable;
    protected $link;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($link)
    {
        $this->link = $link;
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
        $link = $this->link;
        return (new MailMessage)
                    ->subject('Reset password.')
                    ->line('You have received this email because you have requested to change your account password. Click on the following button')
                    ->action('Reset password', url($link))
                    ->line('Thank you for using our application!');
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
