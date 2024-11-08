<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
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
        $mailMessage = (new MailMessage)
        ->subject('NOTIFICATION - Refund request - '. $this->data['service']->name)
                    ->line($this->data['user']->name.' '.$this->data['user']->surname.' has submitted a refund request for a purchased lead. Here are the details:')
                    ->line('Email: '. $this->data['user']->email)
                    ->line('Lead ID: ' .$this->data['lead']->id)
                    ->line('Date: '. $this->data['lead']->created_at)
                    ->line('Requested Refund Amount: USD '.$this->data['service']->price.'$')
                    ->line('Reason for Refund Request: '.$this->data['form']['reason'])
                    ->line('Description: '.$this->data['form']['description']);

                    if ($this->data['image'] != null) {
                        $mailMessage->line('An image was provided with this request:')
                                    ->action('Show image',url($this->data['image'])); // Link directo a la imagen
                    }
                    return $mailMessage;

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
