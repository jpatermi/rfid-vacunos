<?php

namespace App\Http\Controllers;

use App\Lct2;
use Illuminate\Http\Request;

class Lct2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lct2s= Lct2::all()->sortBy('name')->values();
        return response()->json($lct2s, 200);
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
            $lct2 = Lct2::create([
              'name' => $data['name'],
              'lct1_id' => $data['lct1_id']
            ]);
            return response()->json($lct2, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function show($lct2)
    {
        $lct2 = Lct2::find($lct2);
        if($lct2) {
            return response()->json($lct2, 200);
        } else {
            return response()->json(['error' => 'Ubicación 2 no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function edit(Lct2 $lct2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lct2)
    {
        try {
            $data = $request->json()->all();
            $lct2 = Lct2::find($lct2);
            if($lct2) {
                $lct2->name = $data['name'];
                $lct2->lct1_id = $data['lct1_id'];
                $lct2->save();
                return response()->json($lct2, 201);
            } else {
                return response()->json(['error' => 'Ubicación 2 no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lct2 $lct2)
    {
        //
    }
    /**
     * Display the locations 2 belonging to the specific locations 1.
     *
     * @param  \App\Lct2  $lct1_id
     * @return \Illuminate\Http\Response
     */
    public function showByLct1($lct1_id)
    {
        $lct2s = Lct2::where('lct1_id', $lct1_id)->get()->sortBy('name')->values();
        if($lct2s->isNotEmpty()) {
            return response()->json($lct2s, 200);
        } else {
            return response()->json(['error' => 'Ubicación 2 sin Ubicación 1'], 406);
        }
    }
}
