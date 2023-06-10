<?php

namespace App\Notifications;

use App\Models\Bot;
use App\Models\Noticia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramMessageNotify extends Notification
{
    use Queueable;

    protected $bot;
    protected $noticia;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bot $bot, Noticia $noticia)
    {
        $this->bot = $bot;
        $this->noticia = $noticia;
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
        $telMessage = TelegramMessage::create()->token($this->bot->token);

        if ($this->noticia->titulo) $telMessage->line("*{$this->noticia->titulo}:*");
        if ($this->noticia->contenido) $telMessage->line($this->noticia->contenido);

        return $telMessage;
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
