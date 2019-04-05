<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AnimalVaccination;
use App\Animal;

class AnimalVaccinationController extends Controller
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
            $animal = Animal::find($data['animal_id']);
            if($animal) {
                $anivac = $animal->vaccinations()->where('animal_id', $data['animal_id'])
                                       ->where('vaccination_id', $data['vaccination_id'])
                                       ->where('application_date', $data['application_date'])
                                       ->get()
                                       ->first();
                if(!$anivac) {
                    $anivac = AnimalVaccination::create([
                      'animal_id' => $data['animal_id'],
                      'vaccination_id' => $data['vaccination_id'],
                      'application_date' => $data['application_date']
                    ]);
                    return response()->json($anivac, 201);
                } else {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene la vacuna: ' . $anivac->name . ' de fecha: ' . $data['application_date']], 406);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal) {
            $vaccinations = $animal->vaccinations()
                                   ->orderBy('application_date', 'desc')
                                   ->orderBy('name', 'asc')
                                   ->get();
            if($vaccinations->isNotEmpty()) {
                $applications = array();
                foreach ($vaccinations as $vaccination) {
                    $name = $vaccination->name;
                    for ($x = 1; $x <= (45 - strlen($vaccination->name)); $x++) {
                        $name = $name . ' ';
                    }
                    $application = array('id_vaccination' => $vaccination->id,
                                         'application' => $name . $vaccination->pivot->application_date->format('d/m/Y'),
                                         'application_date' => $vaccination->pivot->application_date->format('Y-m-d'),
                                         'id_anivac' => $vaccination->pivot->id);
                    $applications[] = $application;
                }
                return response()->json($applications, 200);
            } else {
                return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha sido vacunado aún'], 406);
            }
        } else {
            return response()->json(['error' => 'Animal no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->json()->all();
            $anivac = AnimalVaccination::find($id);
            if($anivac) {
                $anivac->animal_id = $data['animal_id'];
                $anivac->vaccination_id = $data['vaccination_id'];
                $anivac->application_date = $data['application_date'];
                $anivac->save();
                return response()->json($anivac, 201);
            } else {
                return response()->json(['error' => 'Aplicación de Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $anivacLocated = AnimalVaccination::find($id);
            if($anivacLocated) {
                $anivacLocated->delete();
                return response()->json(['exitoso' => 'Aplicación eliminada con éxito'], 204);
            } else {
                return response()->json(['error' => 'Aplicación de Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
