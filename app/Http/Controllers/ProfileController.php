<?php

namespace App\Http\Controllers;

use App\Helpers\FieldsOptions\RoleFieldOptions;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::query()->withCount(['bots', 'noticias'])->find(Auth::id());

        return view('profile', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => optional(RoleFieldOptions::tryFrom($user->role))->label(),
                'created_at' => Carbon::parse($user->created_at)->locale(config('app.locale'))->isoFormat('DD MMMM Y'),
                'updated_at' => Carbon::parse($user->updated_at)->locale(config('app.locale'))->isoFormat('DD MMMM Y'),
                'bots_count' => $user->bots_count,
                'noticias_count' => $user->noticias_count
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => __('messages.updated')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => __('messages.updated')]);
    }
}
