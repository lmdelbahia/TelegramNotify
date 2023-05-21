<?php

namespace App\Http\Controllers;

use App\Helpers\FieldsOptions\RoleFieldOptions;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        if ($request->ajax()) {
            $data = User::query();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="edit" class="btn btn-primary btn-sm" title="' . __('Editar') . '"><i class="fa-solid fa-edit"></i></a>';

                    $btn = '<a onClick="Show_Token_Dialog(this)" data-id="' . $row->id . '" data-type="token" class="btn btn-primary btn-sm" title="' . __('Token de acceso - API') . '"><i class="fa-solid fa-key"></i></a>';

                    $btn = $btn . ' <a onClick="Async_Get(this)" data-id="' . $row->id . '" data-type="delete" class="btn btn-danger btn-sm" title="' . __('Eliminar') . '"><i class="fa-solid fa-trash"></i></a>';

                    return $btn;
                })
                ->editColumn('role', function ($row) {
                    return optional(RoleFieldOptions::tryFrom($row->role))->label();
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

        return view('user', ['roles' => RoleFieldOptions::toArray()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json(['message' => __('messages.created')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return response()->json(['data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->name =  $request->name;
        $user->email =  $request->email;
        $user->role =  $request->role;
        if ($request->cbxChangePassword) {
            $user->password =  Hash::make($request->password);
        }
        $user->save();

        return response()->json(['message' => __('messages.updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(['message' => __('messages.deleted')]);
    }

    /**
     * Generate a new access token and destroy old.
     */
    public function generateToken(User $user)
    {
        $this->authorize('generateToken', $user);

        $user->tokens()->delete();

        $token = $user->createToken('Token para la generación de contenido');

        return response()->json(['data' => ['token' => $token->plainTextToken]]);
    }
}
