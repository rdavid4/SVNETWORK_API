<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyVerifiedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
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
        return (new MailMessage)
        ->theme('default')
        ->subject('Your company has been successfully verified')
        ->view('mail.company.verification', ['user' => $this->user]);

    //     return (new MailMessage)
    //     ->greeting('Congratulations '.$this->user->name.'!')
    //     ->line('Your company has been successfully verified. You are now officially listed on our platform and visible in search results. This means you can start connecting with new clients and expanding your business opportunities.')
    //     ->line("We're thrilled to have you join our community of verified businesses. If you have any questions or need assistance, feel free to reach out to our support team.")
    //     ->action('Show my company profile', url($this->user->link))
    //     ->line('Thank you for using our application!')
    //     ->line('We invite you to review our terms of use for professionals. <a href="' . url($this->user->link2) . '">Pro Terms</a>.')
    //    ;
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
