<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserTokenRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Noticia::class);

        if ($request->ajax()) {
            $data = Auth::user()->tokens();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = ' <a onClick="Async_Delete(this)" data-id="' . $row->id . '" data-name="' . $row->name . '" data-type="delete" class="btn btn-danger btn-sm" title="' . __('Eliminar') . '"><i class="fa-solid fa-trash"></i></a>';

                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->locale(config('app.locale'))->isoFormat('DD MMM Y h:mm a');
                })
                ->editColumn('last_used_at', function ($row) {
                    return $row->last_used_at ? Carbon::parse($row->last_used_at)->locale(config('app.locale'))->isoFormat('DD MMM Y h:mm a') : "";
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('user-token');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserTokenRequest $request)
    {
        $token = Auth::user()->createToken($request->name);

        return response()->json(['data' => ['token' => $token->plainTextToken]]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT)) {

            Auth::user()->tokens()->where('id', $id)->delete();

            return response()->json(['message' => __('messages.deleted')]);
        }

        return response()->json(['message' => __('http-statuses.404')], 404);
    }
}
