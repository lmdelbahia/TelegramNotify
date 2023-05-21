<?php

namespace App\Jobs;

use App\Models\Noticia;
use App\Models\NoticiaImagen;
use App\Notifications\TelegramMessageNotify;
use App\Notifications\TelegramPhotoNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNoticiaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $noticia;

    /**
     * Create a new job instance.
     */
    public function __construct(Noticia $noticia)
    {
        $this->noticia = $noticia->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->noticia->load(['botDestinations.bot', 'imagenes']);

        foreach ($this->noticia->botDestinations as $botDestination) {
            Notification::route('telegram', $botDestination->identifier)
                ->notify(new TelegramMessageNotify($botDestination->bot, $this->noticia));

            foreach ($this->noticia->imagenes as $imagen) {
                Notification::route('telegram', $botDestination->identifier)
                    ->notify(new TelegramPhotoNotify($botDestination->bot, $imagen));
            }
        }
    }
}
