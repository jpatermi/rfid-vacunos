<?php

namespace App\Http\Controllers;

use App\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vaccination = Vaccination::all()->sortBy('name')->values();
        return response()->json($vaccination, 200);
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
            $vaccination = Vaccination::create([
              'name' => $data['name']
            ]);
            return response()->json($vaccination, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function show($vaccination)
    {
        $vaccination = Vaccination::find($vaccination);
        if($vaccination) {
            return response()->json($vaccination, 200);
        } else {
            return response()->json(['error' => 'Vacuna no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function edit(Vaccination $vaccination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vaccination)
    {
        try {
            $data = $request->json()->all();
            $vaccination = Vaccination::find($vaccination);
            if($vaccination) {
                $vaccination->name = $data['name'];
                $vaccination->save();
                return response()->json($vaccination, 201);
            } else {
                return response()->json(['error' => 'Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function destroy($vaccination)
    {
        try {
            $vaccinationLocated = Vaccination::find($vaccination);
            if($vaccinationLocated) {
                $animals = $vaccinationLocated->animals;
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $vaccinationLocated->delete();
                    return response()->json(['exitoso' => 'Vacuna: ' . $vaccinationLocated->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
