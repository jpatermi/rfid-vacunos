<?php

namespace App\Http\Controllers;

use App\Lct3;
use Illuminate\Http\Request;

class Lct3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lct3s= Lct3::all()->sortBy('name')->values();
        return response()->json($lct3s, 200);
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
            $lct3 = Lct3::create([
              'name' => $data['name']
            ]);
            return response()->json($lct3, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lct3  $lct3
     * @return \Illuminate\Http\Response
     */
    public function show($lct3)
    {
        $lct3= Lct3::find($lct3);
        if($lct3) {
            return response()->json($lct3, 200);
        } else {
            return response()->json(['error' => 'Ubicación 3 no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lct3  $lct3
     * @return \Illuminate\Http\Response
     */
    public function edit(Lct3 $lct3)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lct3  $lct3
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lct3)
    {
        try {
            $data = $request->json()->all();
            $lct3 = Lct3::find($lct3);
            if($lct3) {
                $lct3->name = $data['name'];
                $lct3->save();
                return response()->json($lct3, 201);
            } else {
                return response()->json(['error' => 'Ubicación 3 no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lct3  $lct3
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lct3 $lct3)
    {
        //
    }
}
