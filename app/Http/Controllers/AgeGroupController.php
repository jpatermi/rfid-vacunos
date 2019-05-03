<?php

namespace App\Http\Controllers;

use App\AgeGroup;
use Illuminate\Http\Request;

class AgeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ageGroups = AgeGroup::all()->sortBy('id')->values();
        return response()->json($ageGroups, 200);
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
            $ageGroup = AgeGroup::create([
              'name' => $data['name']
            ]);
            return response()->json($ageGroup, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function show($ageGroup)
    {
        $ageGroup = AgeGroup::find($ageGroup);
        if($ageGroup) {
            return response()->json($ageGroup, 200);
        } else {
            return response()->json(['error' => 'Grupo Etario no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(AgeGroup $ageGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ageGroup)
    {
        try {
            $data = $request->json()->all();
            $ageGroup = AgeGroup::find($ageGroup);
            if($ageGroup) {
                $ageGroup->name = $data['name'];
                $ageGroup->save();
                return response()->json($ageGroup, 201);
            } else {
                return response()->json(['error' => 'Grupo Etario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($ageGroup)
    {
        try {
            $ageGroupLocated = AgeGroup::find($ageGroup);
            if($ageGroupLocated) {
                $animals = Animal::where('age_group_id', $ageGroup)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $ageGroupLocated->delete();
                    return response()->json(['exitoso' => 'Grupo Etario: ' . $ageGroupLocated->name . ' eliminado con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Grupo Etario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Breeds.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalsAgeGroups()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $ageGroups = \App\AgeGroup::withCount('animals')->get()->where("animals_count", ">", 0);
        $totalAgeGroup = array();
        foreach ($ageGroups as $ageGroup) {
            $name   = $ageGroup->name;
            for ($x = 1; $x <= (25 - strlen($ageGroup->name)); $x++)
            {
                $name = $name . "\t";
            }
            $totalAgeGroup[] = $name . $ageGroup->animals_count;
        }
        return response()->json(['totals' => $totalAgeGroup], 200);
    }
}
