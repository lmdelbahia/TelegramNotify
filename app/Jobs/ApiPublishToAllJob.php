<?php

namespace App\Jobs;

use App\Models\Noticia;
use App\Models\NoticiaImagen;
use App\Models\Publication;
use App\Models\User;
use App\Notifications\TelegramMessageNotify;
use App\Notifications\TelegramPhotoNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ApiPublishToAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $publication;


    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Publication $publication)
    {
        $this->user = $user->withoutRelations();
        $this->publication = $publication;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->user->bots as $bot) {
            foreach ($bot->botDestinations as $botDestination) {
                try {
                    Notification::route('telegram', $botDestination->identifier)
                        ->notify(new TelegramMessageNotify($bot, new Noticia([
                            'titulo' => $this->publication->titulo,
                            'contenido' => $this->publication->contenido
                        ])));
                } catch (\Throwable $th) {
                    Log::error($th);
                }

                try {
                    Notification::route('telegram', $botDestination->identifier)
                        ->notify(new TelegramPhotoNotify($bot, new NoticiaImagen([
                            'descripcion' => $this->publication->titulo,
                            'path' => $this->publication->image_path
                        ])));
                } catch (\Throwable $th) {
                    Log::error($th);
                }
            }

            Storage::delete($this->publication->image_path);
            $this->publication->delete();
        }
    }
}
