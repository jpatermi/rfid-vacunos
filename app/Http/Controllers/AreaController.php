<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use App\Animal;
use App\Lct1;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas= Area::all()->sortBy('name')->values();
        return response()->json($areas, 200);
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
            $area = Area::create([
              'name' => $data['name'],
              'farm_id' => $data['farm_id']
            ]);
            return response()->json($area, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show($area)
    {
        $area = Area::find($area);
        if($area) {
            return response()->json($area, 200);
        } else {
            return response()->json(['error' => 'Área no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $area)
    {
        try {

            $data = $request->json()->all();
            $area = Area::find($area);
            if($area) {
                $area->name = $data['name'];
                $area->farm_id = $data['farm_id'];
                $area->save();
                return response()->json($area, 201);
            } else {
                return response()->json(['error' => 'Área no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($area)
    {
        try {
            $areaLocated = Area::find($area);
            if($areaLocated) {
                $animals = Animal::where('area_id', $area)->get();
                $lct1s = Lct1::where('area_id', $area)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                }
                elseif ($lct1s->isNotEmpty()) {
                    $lct1Name = array();
                    foreach ($lct1s as $lct1) {
                        $lct1Name[] = $lct1->name;
                    }
                    return response()->json(['conflicto' => $lct1Name], 409);
                } else {
                    $areaLocated->delete();
                    return response()->json(['exitoso' => 'Area: ' . $areaLocated->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Area no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
