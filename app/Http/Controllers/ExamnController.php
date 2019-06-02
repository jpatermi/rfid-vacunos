<?php

namespace App\Http\Controllers;

use App\Examn;
use Illuminate\Http\Request;

class ExamnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examn = Examn::all()->sortBy('name')->values();
        return response()->json($examn, 200);
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
            $examn = Examn::create([
              'name' => $data['name'],
              'characteristic' => $data['characteristic'],
            ]);
            return response()->json($examn, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function show($examn)
    {
        $examn = Examn::find($examn);
        if($examn) {
            return response()->json($examn, 200);
        } else {
            return response()->json(['error' => 'Examna no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function edit(Examn $examn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $examn)
    {
        try {
            $data = $request->json()->all();
            $examn = Examn::find($examn);
            if($examn) {
                $examn->name = $data['name'];
                $examn->characteristic = $data['characteristic'];
                $examn->save();
                return response()->json($examn, 201);
            } else {
                return response()->json(['error' => 'Examen no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function destroy($examn)
    {
        try {
            $examn = Examn::find($examn);
            if($examn) {
                $animals = $examn->animalExamns;
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $examn->delete();
                    return response()->json(['exitoso' => 'Examen: ' . $examn->name . ' eliminado con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Examen no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Examn.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalExamns()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $examns = \App\Examn::all();
        $totalExamns = array();
        foreach ($examns as $examn)
        {
            $name   = $examn->name;
            $totalExamns[] = $name;
            $animals = $examn->AnimalExamns;
            foreach ($animals as $animal)
            {
                $totalExamns[] = "\t\t" . $animal->animal_rfid . "\t\t\t\t\t\t\t\t" . $animal->pivot->application_date->format('d/m/Y');
            }
        }
        return response()->json(['totals' => $totalExamns], 200);
    }
}
