<?php

namespace App\Http\Controllers;

use App\AnimalLocation;
use Illuminate\Http\Request;
use App\Animal;
use Illuminate\Support\Facades\DB;

class AnimalLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $aniloc = AnimalLocation::create([
              'animal_id' => $data['animal_id'],
              'farm_id' => $data['farm_id'],
              'area_id' => $data['area_id'],
              'lct1_id' => $data['lct1_id'],
              'lct2_id' => $data['lct2_id'],
            ]);
            return response()->json($aniloc, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AnimalLocation  $animalLocation
     * @return \Illuminate\Http\Response
     */
    public function showConJoin($animal_id)
    {
        global $animal_id2;
        $animal_id2 = $animal_id;
        $animal = Animal::find($animal_id);
        if($animal) {
            $locations = DB::table('farms')->join('areas', 'farms.id', '=', 'areas.farm_id')
                                           ->join('lct1s', 'areas.id', '=', 'lct1s.area_id')
                                           ->join('lct2s', 'lct1s.id', '=', 'lct2s.lct1_id')
                                           ->join('animal_locations', function ($join) {global $animal_id2;
                                      $join->on('areas.id', '=', 'animal_locations.area_id')->on('lct1s.id', '=', 'animal_locations.lct1_id')
                                                                                            ->on('lct2s.id', '=', 'animal_locations.lct2_id')
                                                                                            ->where('animal_locations.animal_id', $animal_id2);
                                        })
                                           ->join('animals', 'animals.id', '=', 'animal_locations.animal_id')
                                           ->select('animal_locations.animal_id',
                                                    'animals.animal_rfid',
                                                    'farms.name as farm',
                                                    'areas.name as area',
                                                    'lct1s.name as lct1',
                                                    'lct2s.name as lct2',
                                                    'animal_locations.created_at')
                                           ->get();
            if($locations->isNotEmpty()) {
                return response()->json($locations, 200);
            } else {
                return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha sido cambiado de ubicación aún'], 406);
            }
        } else {
            return response()->json(['error' => 'Animal no existente'], 406);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AnimalLocation  $animalLocation
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $animalLocations = $animal->animalLocations->sortBy('id');
            if($animalLocations->isNotEmpty())
            {
                $locations = array();
                foreach ($animalLocations as $animalLocation)
                {
                    $location = array('id' => $animalLocation->id,
                                      'animal_id' => $animalLocation->animal_id,
                                      'animal_rfid' => $animal->animal_rfid,
                                      'farm' => $animalLocation->farm->name,
                                      'area' => $animalLocation->area->name,
                                      'lct1' => $animalLocation->lct1->name,
                                      'lct2' => $animalLocation->lct2->name,
                                      'created_at' => $animalLocation->created_at->format('d-m-Y H:i:s'));
                    $locations[] = $location;
                }
                return response()->json($locations, 200);
            }
            else
            {
                return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha sido cambiado de ubicación aún'], 406);
            }
        } else {
            return response()->json(['error' => 'Animal no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AnimalLocation  $animalLocation
     * @return \Illuminate\Http\Response
     */
    public function edit(AnimalLocation $animalLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AnimalLocation  $animalLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AnimalLocation $animalLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AnimalLocation  $animalLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(AnimalLocation $animalLocation)
    {
        //
    }
}
