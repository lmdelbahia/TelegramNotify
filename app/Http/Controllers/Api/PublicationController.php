<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublishToAllRequest;
use App\Http\Requests\PublishToBotsRequest;
use App\Http\Requests\StorePublicationRequest;
use App\Jobs\ApiPublicationJob;
use App\Jobs\ApiPublishToAllJob;
use App\Jobs\ApiPublishToBotsJob;
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

    #[Endpoint('Publish to all', 'Publica contenido en todos los Bots y sus respectivos destinos')]
    #[BodyParam('contenido', 'string', "Puede usar Markdown para el formato del contenido, ejemplo: <br/><strong>*bold text*</strong><br/><em>_italic text_</em><br/>[text] (URL)<br>`inline fixed-width code`<br/>```pre-formatted fixed-width code block```", false)]
    #[Response(['message' => 'Su contenido se ha puesto en la cola de salida'])]
    public function toAll(PublishToAllRequest $request)
    {
        $path = $request->image->store('public/publications');

        $publication = Publication::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'image_path' => $path
        ]);

        ApiPublishToAllJob::dispatch(Auth::user(), $publication);

        return response()->json(['message' => __('messages.published')]);
    }

    #[Endpoint('Publish to Bots', 'Publica contenido en todos los Bots seleccionados y sus respectivos destinos')]
    #[BodyParam('contenido', 'string', "Puede usar Markdown para el formato del contenido, ejemplo: <br/><strong>*bold text*</strong><br/><em>_italic text_</em><br/>[text] (URL)<br>`inline fixed-width code`<br/>```pre-formatted fixed-width code block```", false)]
    #[Response(['message' => 'Su contenido se ha puesto en la cola de salida'])]
    public function toBots(PublishToBotsRequest $request)
    {
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->image->store('public/publications');
        }

        $publication = Publication::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'image_path' => $path
        ]);

        ApiPublishToBotsJob::dispatch($request->bots, $publication);

        return response()->json(['message' => __('messages.published')]);
    }
}
