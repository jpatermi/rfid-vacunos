<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $data = $request->json()->all();
            $user = User::create([
              'name' => $data['name'],
              'username' => $data['username'],
              'email' => $data['email'],
              'password' => Hash::make($data['password']),
              'api_token' => str_random(60)
            ]);
            return response()->json($user, 201);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
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

