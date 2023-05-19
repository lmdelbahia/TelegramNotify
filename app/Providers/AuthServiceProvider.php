<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Bot;
use App\Models\BotDestination;
use App\Models\Noticia;
use App\Models\NoticiaImagen;
use App\Models\User;
use App\Policies\BotDestinationPolicy;
use App\Policies\BotPolicy;
use App\Policies\NoticiaImagenPolicy;
use App\Policies\NoticiaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Noticia::class => NoticiaPolicy::class,
        NoticiaImagen::class => NoticiaImagenPolicy::class,
        Bot::class => BotPolicy::class,
        BotDestination::class => BotDestinationPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
