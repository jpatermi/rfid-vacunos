<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Farm;
use Caffeinated\Shinobi\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.index')->only('index');
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.show')->only('show');
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->roles;
        }
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($users, 200);
        } else {
            return view('configuration.users.indexUser', compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $farms = Farm::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        return view('configuration.users.createUser', compact('farms', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'username'  => ['required', 'string', 'min:6', 'max:10'],
            'farm_id'   => ['required'],
            'roles'     => ['required'],
            ]);
            $user = User::where('email', $data['email'])->get();
            if ($user->isEmpty()) {
                $user = User::where('username', $data['username'])->get();
                if ($user->isEmpty()) {
                    $user = User::create([
                      'name'        => $data['name'],
                      'username'    => $data['username'],
                      'email'       => $data['email'],
                      'password'    => Hash::make($data['password']),
                      'api_token'   => str_random(60),
                      'farm_id'     => $data['farm_id'],
                    ]);

                    $user->assignRoles($data['roles']);

                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json($user, 201);
                    } else {
                        return redirect()->route('users.edit', $user->id)->with('info', 'Usuario guardado con éxito');
                    }
                } else {
                    return redirect()->route('users.create')->with('warning', 'El usuario: ' . $user->first()->username . ' ya existe');
                }
            } else {
                return redirect()->route('users.create')->with('warning', 'El correo: ' . $user->first()->email . ' ya existe');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $farms = Farm::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        return view('configuration.users.editUser', compact('farms', 'roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255'],
            'password'  => [''],
            'username'  => ['required', 'string', 'min:6', 'max:10'],
            'farm_id'   => ['required'],
            'roles'     => ['required'],
            ]);

            $user->name     = $data['name'];
            $user->username = $data['username'];
            $user->email    = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->api_token= str_random(60);
            $user->farm_id  = $data['farm_id'];
            $user->save();

            $user->syncRoles($data['roles']);

            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($user, 201);
            } else {
                return redirect()->route('users.edit', $user->id)->with('info', 'Usuario actualizado con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->animals->isNotEmpty()) {
            return response()->json(['error' => 'El Usuario: ' . $user->username . ' tiene Aimales'], 409);
        /*} elseif ($user->roles->isNotEmpty()) {
            return response()->json(['error' => 'El Usuario: ' . $user->username . ' tiene Roles'], 409);*/
        }
        /*** Se Eliminan también los Roles del Usuario ***/
        $roles = $user->roles;
        foreach ($roles as $role)
        {
            $user->removeRoles($role->slug);
        }

        $user->delete();

        if (request()->header('Content-Type') == 'application/json') {
            return response()->json(['exitoso' => 'Usuario: ' . $user->username . ' eliminado con éxito'], 204);
        } else {
            return redirect()->route('users.index')->with('info', 'Usuario eliminado con éxito');
        }
    }
    /**
     * Get the token resource from login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginGetToken(Request $request)
    {
        try {
            $data = $request->json()->all();
            $user = User::where('username', $data['username'])->first();
            if($user && Hash::check($data['password'], $user->password)) {
                return response()->json($user, 200);
            }
            else {
                return response()->json(['error' => 'Usuario o Clave inválida'], 406);
            }

        } catch (ModelNotFoundException $e){
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}

