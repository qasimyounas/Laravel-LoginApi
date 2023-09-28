<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
class MyNotification extends Notification
{
    use Queueable;
    private $forget;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($forget)
    {
        $this->forget = $forget;
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
        return (new MailMessage)
            ->from($address = 'maxsahil40@gmail.com', $name = 'chatbot')
            ->subject('Forget Password')
            ->greeting('  ') // Pass an empty string to remove the greeting

            ->line(new HtmlString('<strong>Clik on this url for reset your password </strong>'))
            ->action('Notification Action', url('https://chatbot.cyberasol.com/'));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
