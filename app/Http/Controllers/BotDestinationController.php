<?php

namespace App\Http\Controllers;

use App\Models\BotDestination;
use App\Http\Requests\StoreBotDestinationRequest;
use App\Http\Requests\UpdateBotDestinationRequest;
use App\Models\Bot;
use App\Models\Noticia;
use App\Notifications\TelegramMessageNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class BotDestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Bot $bot)
    {
        $this->authorize('viewAny', BotDestination::class);

        if ($request->ajax()) {
            $data = $bot->botDestinations();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="edit" class="btn btn-primary btn-sm" title="' . __('Editar') . '"><i class="fa-solid fa-edit"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Test(this)" data-id="' . $row->id . '" data-type="test" class="btn btn-light btn-sm" title="' . __('Probar') . '"><i class="fa-solid fa-paper-plane text-primary"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="delete" class="btn btn-danger btn-sm" title="' . __('Eliminar') . '"><i class="fa-solid fa-trash"></i></a>';

                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->locale(config('app.locale'))->isoFormat('DD MMM Y h:mm a');
                })
                ->editColumn('updated_at', function ($row) {
                    return Carbon::parse($row->created_at)->locale(config('app.locale'))->isoFormat('DD MMM Y h:mm a');
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('bot-destination', ['bot' => $bot->withoutRelations()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBotDestinationRequest $request)
    {
        $this->authorize('create', BotDestination::class);

        BotDestination::create([
            'bot_id' => $request->bot_id,
            'name' => $request->name,
            'identifier' => $request->identifier
        ]);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BotDestination $botDestination)
    {
        $this->authorize('view', $botDestination);

        return response()->json(['data' => $botDestination]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBotDestinationRequest $request, BotDestination $botDestination)
    {
        $this->authorize('update', $botDestination);

        $botDestination->bot_id =  $request->bot_id;
        $botDestination->name =  $request->name;
        $botDestination->identifier =  $request->identifier;
        $botDestination->save();

        return response()->json(['message' => __('messages.updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BotDestination $botDestination)
    {
        $this->authorize('delete', $botDestination);

        $botDestination->delete();

        return response()->json(['message' => __('messages.deleted')]);
    }

    /**
     * Test the specified resource.
     */
    public function test(BotDestination $botDestination)
    {
        $this->authorize('test', $botDestination);

        try {
            Notification::route('telegram', $botDestination->identifier)
                ->notify(new TelegramMessageNotify($botDestination->bot, new Noticia([
                    'titulo' => 'Mensaje de prueba',
                    'contenido' => 'Su Bot funciona correctamente'
                ])));

            return response()->json(['message' => __("Mensaje enviado con éxito.")]);
        } catch (\Throwable $th) {
            return response()->json(['message' => __($th->getMessage())], 400);
        }
    }
}
