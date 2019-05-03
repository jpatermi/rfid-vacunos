<?php

namespace App\Http\Controllers;

use App\Dewormer;
use Illuminate\Http\Request;

class DewormerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dewormer = Dewormer::all()->sortBy('name')->values();
        return response()->json($dewormer, 200);
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
            $dewormer = Dewormer::create([
              'name' => $data['name'],
              'characteristic' => $data['characteristic'],
            ]);
            return response()->json($dewormer, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function show($dewormer)
    {
        $dewormer = Dewormer::find($dewormer);
        if($dewormer) {
            return response()->json($dewormer, 200);
        } else {
            return response()->json(['error' => 'Desparasitante no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function edit(Dewormer $dewormer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $dewormer)
    {
        try {
            $data = $request->json()->all();
            $dewormer = Dewormer::find($dewormer);
            if($dewormer) {
                $dewormer->name = $data['name'];
                $dewormer->characteristic = $data['characteristic'];
                $dewormer->save();
                return response()->json($dewormer, 201);
            } else {
                return response()->json(['error' => 'Desparasitante no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function destroy($dewormer)
    {
        try {
            $dewormer = Dewormer::find($dewormer);
            if($dewormer) {
                $animals = $dewormer->animalDewormers;
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $dewormer->delete();
                    return response()->json(['exitoso' => 'Desparasitante: ' . $dewormer->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Desparasitante no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Dewormer.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalDewormers()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $dewormers = \App\Dewormer::all();
        $totalDewormers = array();
        foreach ($dewormers as $dewormer)
        {
            $name   = $dewormer->name;
            $totalDewormers[] = $name;
            $animals = $dewormer->animalDewormers;
            foreach ($animals as $animal)
            {
                $totalDewormers[] = "\t\t" . $animal->animal_rfid . "\t\t\t\t\t\t\t\t" . $animal->pivot->application_date->format('d/m/Y');
            }
        }
        return response()->json(['totals' => $totalDewormers], 200);
    }
}
