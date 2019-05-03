<?php

namespace App\Http\Controllers;

use App\Vitamin;
use Illuminate\Http\Request;

class VitaminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vitamin = Vitamin::all()->sortBy('name')->values();
        return response()->json($vitamin, 200);
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
            $vitamin = Vitamin::create([
              'name' => $data['name'],
              'characteristic' => $data['characteristic'],
            ]);
            return response()->json($vitamin, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vitamin  $vitamin
     * @return \Illuminate\Http\Response
     */
    public function show($vitamin)
    {
        $vitamin = Vitamin::find($vitamin);
        if($vitamin) {
            return response()->json($vitamin, 200);
        } else {
            return response()->json(['error' => 'Vitamina no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vitamin  $vitamin
     * @return \Illuminate\Http\Response
     */
    public function edit(Vitamin $vitamin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vitamin  $vitamin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vitamin)
    {
        try {
            $data = $request->json()->all();
            $vitamin = Vitamin::find($vitamin);
            if($vitamin) {
                $vitamin->name = $data['name'];
                $vitamin->characteristic = $data['characteristic'];
                $vitamin->save();
                return response()->json($vitamin, 201);
            } else {
                return response()->json(['error' => 'Vitamina no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vitamin  $vitamin
     * @return \Illuminate\Http\Response
     */
    public function destroy($vitamin)
    {
        try {
            $vitamin = Vitamin::find($vitamin);
            if($vitamin) {
                $animals = $vitamin->animalVitamins;
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $vitamin->delete();
                    return response()->json(['exitoso' => 'Vitamina: ' . $vitamin->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Vitamina no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Vitamin.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalVitamins()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $vitamins = \App\Vitamin::all();
        $totalVitamins = array();
        foreach ($vitamins as $vitamin)
        {
            $name   = $vitamin->name;
            $totalVitamins[] = $name;
            $animals = $vitamin->AnimalVitamins;
            foreach ($animals as $animal)
            {
                $totalVitamins[] = "\t\t" . $animal->animal_rfid . "\t\t\t\t\t\t\t\t" . $animal->pivot->application_date->format('d/m/Y');
            }
        }
        return response()->json(['totals' => $totalVitamins], 200);
    }
}
