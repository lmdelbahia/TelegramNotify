<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBotRequest;
use App\Http\Requests\UpdateBotRequest;
use App\Models\Bot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Bot::class);

        if ($request->ajax()) {
            $data = Bot::query()->with('user');
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $link = route('bot-destination.index', [$row->id]);

                    $btn = '<a onClick="Asinc_Get(this)" data-id="' . $row->id . '" data-type="edit" class="btn btn-primary btn-sm" title="' . __('Editar') . '"><i class="fa-solid fa-edit"></i></a>';

                    $btn = $btn . " <a href='{$link}'" . 'class="btn btn-secondary btn-sm" title="' . __('Destinos') . '"><i class="fa-solid fa-envelopes-bulk"></i></a>';

                    $btn = $btn . ' <a onClick="Asinc_Get(this)" data-id="' . $row->id . '" data-type="delete" class="btn btn-danger btn-sm" title="' . __('Eliminar') . '"><i class="fa-solid fa-trash"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('bot', ['users' => User::select(['id', 'name'])->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBotRequest $request)
    {
        $this->authorize('create', Bot::class);

        Bot::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'token' => $request->token
        ]);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bot $bot)
    {
        $this->authorize('view', $bot);

        return response()->json(['data' => $bot]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBotRequest $request, Bot $bot)
    {
        $this->authorize('update', $bot);

        $bot->user_id =  $request->user_id;
        $bot->name =  $request->name;
        $bot->token =  $request->token;
        $bot->save();

        return response()->json(['message' => __('messages.updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bot $bot)
    {
        $this->authorize('delete', $bot);

        $bot->delete();

        return response()->json(['message' => __('messages.deleted')]);
    }
}
