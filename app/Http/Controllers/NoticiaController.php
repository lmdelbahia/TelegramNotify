<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\UpdateNoticiaRequest;
use App\Jobs\SendNoticiaJob;
use App\Models\Noticia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Noticia::class);

        if ($request->ajax()) {
            $data = Auth::user()->noticias()->select(['id', 'titulo', 'created_at', 'updated_at']);
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $link = route('noticia-imagen.index', [$row->id]);

                    $btn = '<a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="edit" class="btn btn-primary btn-sm" title="' . __('Editar') . '"><i class="fa-solid fa-edit"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="details" class="btn btn-secondary btn-sm" title="' . __('Detalles') . '"><i class="fa-solid fa-clipboard-list"></i></a>';

                    $btn = $btn . " <a href='{$link}'" . 'class="btn btn-dark btn-sm" title="' . __('Imágenes') . '"><i class="fa-solid fa-images"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Send(this)" data-id="' . $row->id . '" data-type="test" class="btn btn-light btn-sm" title="' . __('Enviar') . '"><i class="fa-solid fa-paper-plane text-primary"></i></a>';

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

        return view('noticia', [
            'bots' => Auth::user()->bots()->with(['botDestinations'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoticiaRequest $request)
    {
        $this->authorize('create', Noticia::class);

        $noticia = Noticia::create([
            'user_id' => Auth::id(),
            'titulo' => $request->titulo,
            'contenido' => $request->contenido
        ]);

        $noticia->botDestinations()->sync($request->botDestinations);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Noticia $noticia)
    {
        $this->authorize('view', $noticia);

        return response()->json(['data' => $noticia->load(['botDestinations:id'])]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoticiaRequest $request, Noticia $noticia)
    {
        $this->authorize('update', $noticia);

        $noticia->titulo =  $request->titulo;
        $noticia->contenido =  $request->contenido;
        $noticia->save();

        $noticia->botDestinations()->sync($request->botDestinations);

        return response()->json(['message' => __('messages.updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Noticia $noticia)
    {
        $this->authorize('delete', $noticia);

        $noticia->delete();

        return response()->json(['message' => __('messages.deleted')]);
    }

    /**
     * Send to Telegram the specified resource.
     */
    public function send(Noticia $noticia)
    {
        $this->authorize('send', $noticia);

        try {
            SendNoticiaJob::dispatch($noticia);

            return response()->json(['message' => __("messages.published")]);
        } catch (\Throwable $th) {
            return response()->json(['message' => __($th->getMessage())], 400);
        }
    }
}
