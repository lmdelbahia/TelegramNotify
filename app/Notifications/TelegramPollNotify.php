<?php

namespace App\Notifications;

use App\Models\Bot;
use App\Models\Encuesta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramPoll;

class TelegramPollNotify extends Notification
{
    use Queueable;

    protected $bot;
    protected $encuesta;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bot $bot, Encuesta $encuesta)
    {
        $this->bot = $bot;
        $this->encuesta = $encuesta;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toTelegram($notifiable)
    {
        return TelegramPoll::create()
            ->token($this->bot->token)
            ->question($this->encuesta->titulo)
            ->choices(explode("**", $this->encuesta->opciones));
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
