<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\UpdateNoticiaRequest;
use App\Models\Noticia;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            $data = Noticia::query()->select(['id', 'titulo', 'created_at', 'updated_at']);
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $link = route('noticia-imagen.index', [$row->id]);

                    $btn = '<a onClick="Asinc_Get(this)" data-id="' . $row->id . '" data-type="edit" class="btn btn-primary btn-sm" title="' . __('Editar') . '"><i class="fa-solid fa-edit"></i></a>';

                    $btn = $btn . ' <a onClick="Asinc_Get(this)" data-id="' . $row->id . '" data-type="details" class="btn btn-secondary btn-sm" title="' . __('Detalles') . '"><i class="fa-solid fa-clipboard-list"></i></a>';

                    $btn = $btn . " <a href='{$link}'" . 'class="btn btn-secondary btn-sm" title="' . __('Destinos') . '"><i class="fa-solid fa-images"></i></a>';

                    $btn = $btn . ' <a onClick="Asinc_Get(this)" data-id="' . $row->id . '" data-type="delete" class="btn btn-danger btn-sm" title="' . __('Eliminar') . '"><i class="fa-solid fa-trash"></i></a>';

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

        return view('noticia');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoticiaRequest $request)
    {
        $this->authorize('create', Noticia::class);

        Noticia::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido
        ]);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Noticia $noticia)
    {
        $this->authorize('view', $noticia);

        return response()->json(['data' => $noticia]);
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
}
