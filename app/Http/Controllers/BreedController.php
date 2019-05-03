<?php

namespace App\Http\Controllers;

use App\Breed;
use App\Animal;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breeds = Breed::all()->sortBy('name')->values();
        return response()->json($breeds, 200);
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
            $breed = Breed::create([
              'name' => $data['name']
            ]);
            return response()->json($breed, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function show($breed)
    {
        $breed = Breed::find($breed);
        if($breed) {
            return response()->json($breed, 200);
        } else {
            return response()->json(['error' => 'Raza no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function edit(Breed $breed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $breed)
    {
        try {
            $data = $request->json()->all();
            $breed = Breed::find($breed);
            if($breed) {
                $breed->name = $data['name'];
                $breed->save();
                return response()->json($breed, 201);
            } else {
                return response()->json(['error' => 'Raza no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function destroy($breed)
    {
        try {
            $breedLocated = Breed::find($breed);
            if($breedLocated) {
                $animals = Animal::where('breed_id', $breed)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $breedLocated->delete();
                    return response()->json(['exitoso' => 'Raza: ' . $breedLocated->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Raza no existente'], 406);
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
    public function totalAnimalsBreeds()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $breeds = \App\Breed::withCount('animals')->get()->where("animals_count", ">", 0);
        $totalBreeds = array();
        foreach ($breeds as $breed) {
            $name   = $breed->name;
            for ($x = 1; $x <= (26 - strlen($breed->name)); $x++)
            {
                $name = $name . "\t";
            }
            $totalBreeds[] = $name . $breed->animals_count;
        }
        return response()->json(['totals' => $totalBreeds], 200);
    }
}
