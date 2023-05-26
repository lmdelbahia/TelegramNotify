<?php

namespace App\Jobs;

use App\Models\Encuesta;
use App\Notifications\TelegramPollNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendEncuestaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $encuesta;

    /**
     * Create a new job instance.
     */
    public function __construct(Encuesta $encuesta)
    {
        $this->encuesta = $encuesta->withoutRelations();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->encuesta->load(['botDestinations.bot']);

        foreach ($this->encuesta->botDestinations as $botDestination) {
            try {
                Notification::route('telegram', $botDestination->identifier)
                    ->notify(new TelegramPollNotify($botDestination->bot, $this->encuesta));
            } catch (\Throwable $th) {
                Log::error($th);
            }
        }
    }
}
