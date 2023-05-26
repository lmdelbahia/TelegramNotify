<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Http\Requests\StoreEncuestaRequest;
use App\Http\Requests\UpdateEncuestaRequest;
use App\Jobs\SendEncuestaJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Encuesta::class);

        if ($request->ajax()) {
            $data = Auth::user()->encuestas()->select(['id', 'titulo', 'created_at', 'updated_at']);
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $link = route('noticia-imagen.index', [$row->id]);

                    $btn = '<a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="edit" class="btn btn-primary btn-sm" title="' . __('Editar') . '"><i class="fa-solid fa-edit"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="details" class="btn btn-secondary btn-sm" title="' . __('Detalles') . '"><i class="fa-solid fa-clipboard-list"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Send(this)" data-id="' . $row->id . '" data-titulo="' . $row->titulo . '" data-type="test" class="btn btn-light btn-sm" title="' . __('Enviar') . '"><i class="fa-solid fa-paper-plane text-primary"></i></a>';

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

        return view('encuesta', [
            'bots' => Auth::user()->bots()->with(['botDestinations'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEncuestaRequest $request)
    {
        $this->authorize('create', Encuesta::class);

        $encuesta = Encuesta::create([
            'user_id' => Auth::id(),
            'titulo' => $request->titulo,
            'opciones' => $request->opciones
        ]);

        $encuesta->botDestinations()->sync($request->botDestinations);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Encuesta $encuesta)
    {
        $this->authorize('view', $encuesta);

        return response()->json(['data' => $encuesta->load(['botDestinations:id'])]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEncuestaRequest $request, Encuesta $encuesta)
    {
        $this->authorize('update', $encuesta);

        $encuesta->titulo =  $request->titulo;
        $encuesta->opciones =  $request->opciones;
        $encuesta->save();

        $encuesta->botDestinations()->sync($request->botDestinations);

        return response()->json(['message' => __('messages.updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encuesta $encuesta)
    {
        $this->authorize('delete', $encuesta);

        $encuesta->delete();

        return response()->json(['message' => __('messages.deleted')]);
    }

    /**
     * Send to Telegram the specified resource.
     */
    public function send(Encuesta $encuesta)
    {
        $this->authorize('send', $encuesta);

        SendEncuestaJob::dispatch($encuesta);

        return response()->json(['message' => __("messages.published")]);
    }
}
