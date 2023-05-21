<?php

namespace App\Policies;

use App\Models\BotDestination;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BotDestinationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isClient();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BotDestination $botDestination): bool
    {
        return $botDestination->bot->user_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BotDestination $botDestination): bool
    {
        return $botDestination->bot->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BotDestination $botDestination): bool
    {
        return $botDestination->bot->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BotDestination $botDestination): bool
    {
        return $botDestination->bot->user_id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BotDestination $botDestination): bool
    {
        return $botDestination->bot->user_id == $user->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function test(User $user, BotDestination $botDestination): bool
    {
        return $botDestination->bot->user_id == $user->id;
    }
}
