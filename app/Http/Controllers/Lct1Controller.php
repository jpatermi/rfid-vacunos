<?php

namespace App\Http\Controllers;

use App\Lct1;
use Illuminate\Http\Request;
use App\Animal;
use App\Lct2;

class Lct1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lct1s= Lct1::all()->sortBy('name')->values();
        return response()->json($lct1s, 200);
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
            $lct1 = Lct1::create([
              'name' => $data['name'],
              'area_id' => $data['area_id']
            ]);
            return response()->json($lct1, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function show($lct1)
    {
        $lct1 = Lct1::find($lct1);
        if($lct1) {
            return response()->json($lct1, 200);
        } else {
            return response()->json(['error' => 'Ubicación 1 no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function edit(Lct1 $lct1)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lct1)
    {
        try {
            $data = $request->json()->all();
            $lct1 = Lct1::find($lct1);
            if($lct1) {
                $lct1->name = $data['name'];
                $lct1->area_id = $data['area_id'];
                $lct1->save();
                return response()->json($lct1, 201);
            } else {
                return response()->json(['error' => 'Ubicación 1 no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function destroy($lct1)
    {
        try {
            $lct1Located = Lct1::find($lct1);
            if($lct1Located) {
                $animals = Animal::where('lct1_id', $lct1)->get();
                $lct2s = Lct2::where('lct1_id', $lct1)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                }
                elseif ($lct2s->isNotEmpty()) {
                    $lct2Name = array();
                    foreach ($lct2s as $lct2) {
                        $lct2Name[] = $lct2->name;
                    }
                    return response()->json(['conflicto' => $lct2Name], 409);
                } else {
                    $lct1Located->delete();
                    return response()->json(['exitoso' => 'Ubicación UNO: ' . $lct1Located->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Ubicación UNO no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display the locations 1 belonging to the specific area.
     *
     * @param  \App\Lct1  $area_id
     * @return \Illuminate\Http\Response
     */
    public function showByArea($area_id)
    {
        $lct1s = Lct1::where('area_id', $area_id)->get()->sortBy('name')->values();
        if($lct1s->isNotEmpty()) {
            return response()->json($lct1s, 200);
        } else {
            return response()->json(['error' => 'Ubicación 1 sin Area'], 406);
        }
    }
}
