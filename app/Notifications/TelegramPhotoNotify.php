<?php

namespace App\Notifications;

use App\Models\Bot;
use App\Models\NoticiaImagen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;

class TelegramPhotoNotify extends Notification
{
    use Queueable;

    protected $bot;
    protected $noticiaImagen;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bot $bot, NoticiaImagen $noticiaImagen)
    {
        $this->bot = $bot;
        $this->noticiaImagen = $noticiaImagen;
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
        $path = storage_path("app/{$this->noticiaImagen->path}");
        $descripcion = $this->noticiaImagen->descripcion ?? "";
        return TelegramFile::create()
            ->token($this->bot->token)
            ->content("*{$descripcion}*")
            ->photo($path); // local photo;
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
