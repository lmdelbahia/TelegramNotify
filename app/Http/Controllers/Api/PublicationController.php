<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicationRequest;
use App\Jobs\ApiPublicationJob;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Publication', 'Endpoints para la publicación en Telegram')]
class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    #[Endpoint('Publish', 'Publica contenido en los canales asignados a sus Bots')]
    #[BodyParam('contenido', 'string', "Puede usar Markdown para el formato del contenido, ejemplo: <br/><strong>*bold text*</strong><br/><em>_italic text_</em><br/>[text] (URL)<br>`inline fixed-width code`<br/>```pre-formatted fixed-width code block```")]
    #[Response(['message' => 'Su contenido se ha puesto en la cola de salida'])]
    public function store(StorePublicationRequest $request)
    {
        $path = $request->image_path->store('public/publications');

        $publication = Publication::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'image_path' => $path
        ]);

        ApiPublicationJob::dispatch(Auth::user(), $publication);

        return response()->json(['message' => __('messages.published')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
