<?php

namespace App\Http\Controllers;

use App\PhysicalCharacteristic;
use Illuminate\Http\Request;
use App\Animal;

class PhysicalCharacteristicController extends Controller
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
            $data = $request->validate([
                'animal_id'         => 'required',
                'characteristic'    => 'required',
            ]);

            $animal = Animal::find($data['animal_id']);
            if($animal) {
                $physicalCharacteristic = PhysicalCharacteristic::create([
                  'animal_id' => $data['animal_id'],
                  'characteristic' => $data['characteristic'],
                ]);
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json($physicalCharacteristic, 201);
                }
                else
                {
                    return redirect()->route('animals.edit', $animal->id);
                }
            } else {
                return response()->json(['error' => 'Animal no existente'], 406);
            }

        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PhysicalCharacteristic  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $physicalCharacteristics = $animal->physicalCharacteristics()
                                              ->orderBy('id')
                                              ->get();
            if (request()->header('Content-Type') == 'application/json')
            {
                return response()->json($physicalCharacteristics, 200);
            }
            else
            {
                $i=0;
                return view('physicalCharacteristics.showphysicalCharacteristics', compact('physicalCharacteristics', 'i'));
            }
        }
        else
        {
            return response()->json(['error' => 'Animal no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PhysicalCharacteristic  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function edit(PhysicalCharacteristic $animalVitamin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PhysicalCharacteristic  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->json()->all();
            $physicalCharacteristic = PhysicalCharacteristic::find($id);
            if($physicalCharacteristic) {
                $physicalCharacteristic->animal_id = $data['animal_id'];
                $physicalCharacteristic->characteristic = $data['characteristic'];
                $physicalCharacteristic->save();
                return response()->json($physicalCharacteristic, 201);
            } else {
                return response()->json(['error' => 'Característica no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PhysicalCharacteristic  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $physicalCharacteristic = PhysicalCharacteristic::find($id);
            if($physicalCharacteristic) {
                $physicalCharacteristic->delete();
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json(['exitoso' => 'Característica eliminada con éxito'], 204);
                }
                else
                {
                    return redirect()->route('animals.edit', $physicalCharacteristic->animal_id);
                }
            } else {
                return response()->json(['error' => 'Característica no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
