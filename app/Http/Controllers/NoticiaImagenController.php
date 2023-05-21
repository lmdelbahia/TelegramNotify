<?php

namespace App\Http\Controllers;

use App\Models\NoticiaImagen;
use App\Http\Requests\StoreNoticiaImagenRequest;
use App\Http\Requests\UpdateNoticiaImagenRequest;
use App\Models\Noticia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NoticiaImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Noticia $noticia)
    {
        $this->authorize('viewAny', NoticiaImagen::class);

        if ($request->ajax()) {
            $data = $noticia->imagenes();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = ' <a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="delete" class="btn btn-danger btn-sm" title="' . __('Eliminar') . '"><i class="fa-solid fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('imagen', function ($row) {
                    return '<a onClick="showImage(this)" data-url="' . url(Storage::url($row->path)) . '" data-type="show" class="btn btn-dark btn-lg btn-block" title="' . __('Mostrar') . '"><i class="fa-solid fa-image text-white"></i></a>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->locale(config('app.locale'))->isoFormat('DD MMM Y h:mm a');
                })
                ->rawColumns(['imagen', 'action'])
                ->toJson();
        }

        return view('noticia-imagen', ['noticia' => $noticia->withoutRelations()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoticiaImagenRequest $request)
    {
        $this->authorize('create', NoticiaImagen::class);

        $path = $request->imagen->store('public/noticia_' . $request->noticia_id);

        NoticiaImagen::create([
            'noticia_id' => $request->noticia_id,
            'path' => $path,
            'descripcion' => $request->descripcion
        ]);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(NoticiaImagen $noticiaImagen)
    {
        $this->authorize('view', $noticiaImagen);

        return response()->json(['data' => $noticiaImagen]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoticiaImagenRequest $request, NoticiaImagen $noticiaImagen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NoticiaImagen $noticiaImagen)
    {
        $this->authorize('delete', $noticiaImagen);

        Storage::delete($noticiaImagen->path);

        $noticiaImagen->delete();

        return response()->json(['message' => __('messages.deleted')]);
    }
}
