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
        return TelegramMessage::create()
            ->token($this->bot->token)
            ->line("*{$this->noticia->titulo}:*")
            ->line($this->noticia->contenido);

        /*$imagenes = $this->noticia->imagenes;
        if ($imagenes->count()) {

            $imagesOk = $imagenes->map(function ($item) {
                return Storage::disk('public')->exists($item->path);
            });

            return $telegramFile = TelegramFile::create()
                ->token($this->bot->token)
                ->content('*' . $this->noticia->titulo . ':* ' . $this->noticia->contenido)
                ->photo(public_path() . $item->path); // local photo;
        } else {
        }*/
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
