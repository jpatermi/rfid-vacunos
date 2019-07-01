<?php

namespace App\Http\Controllers;

use App\AnimalDewormer;
use Illuminate\Http\Request;
use App\Animal;
use App\Dewormer;

class AnimalDewormerController extends Controller
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
                'animal_id'        => 'required',
                'dewormer_id'      => 'required',
                'dose'             => 'required|numeric',
                'application_date' => 'required|date',
            ]);
            $animal = Animal::find($data['animal_id']);
            if($animal) {
                $anidesp = $animal->dewormers()
                                  ->where('animal_id', $data['animal_id'])
                                  ->where('dewormer_id', $data['dewormer_id'])
                                  ->where('application_date', $data['application_date'])
                                  ->get()
                                  ->first();
                if(!$anidesp) {
                    $anidesp = AnimalDewormer::create([
                      'animal_id' => $data['animal_id'],
                      'dewormer_id' => $data['dewormer_id'],
                      'dose' => $data['dose'],
                      'application_date' => $data['application_date'],
                    ]);
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json($anidesp, 201);
                    } else {
                        return redirect()->route('animaldewormer.show', $anidesp->animal_id)->with('info', 'Aplicación guardada con éxito');
                    }
                } else {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene el Desparasitante: ' . $anidesp->name . ' de fecha: ' . $anidesp->pivot->application_date->format('d/m/Y')], 406);
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
     * @param  \App\AnimalDewormer  $animalDewormer
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $dewormersAnimal = $animal->dewormers()
                                      ->orderBy('application_date', 'desc')
                                      ->orderBy('name', 'asc')
                                      ->get();
            if($dewormersAnimal->isNotEmpty())
            {
                $applications = array();
                foreach ($dewormersAnimal as $dewormer)
                {
                    $application = array('id_vac_desp_vit'     => $dewormer->id,
                                         'name_vac_desp_vit'   => $dewormer->name,
                                         'dose'                => $dewormer->pivot->dose,
                                         'application_date'    => $dewormer->pivot->application_date->format('d/m/Y'),
                                         'id_ani_vac_desp_vit' => $dewormer->pivot->id);
                    $applications[] = $application;
                }
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($applications, 200);
                } else {
                    $vaccinations = Dewormer::orderBy('name')->get();
                    //$applications = $dewormersAnimal;
                    $model        = 'animaldewormer';
                    $title        = 'Desparasitantes aplicados';
                    $label        = 'Desparasitante';
                    $nomCampoVacDewVit = 'dewormer_id';
                    return view('animalVacDewVit.showAnimalVacDewVit', compact('applications', 'animal', 'model', 'title', 'vaccinations', 'label', 'nomCampoVacDewVit'));
                }
            }
            else
            {
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha sido desparasitado aún'], 406);
                } else {
                    $vaccinations = Dewormer::orderBy('name')->get();
                    $applications = '';
                    $model        = 'animaldewormer';
                    $title        = 'Desparasitantes aplicados';
                    $label        = 'Desparasitante';
                    $nomCampoVacDewVit = 'dewormer_id';
                    return view('animalVacDewVit.showAnimalVacDewVit', compact('applications', 'animal', 'model', 'title', 'vaccinations', 'label', 'nomCampoVacDewVit'));
                }
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
     * @param  \App\AnimalDewormer  $animalDewormer
     * @return \Illuminate\Http\Response
     */
    public function edit(AnimalDewormer $animalDewormer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AnimalDewormer  $animalDewormer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'animal_id' => 'required',
                'dewormer_id' => 'required',
                'dose' => 'required|numeric',
                'application_date' => 'required|date',
            ]);
            $anidesp = AnimalDewormer::find($id);
            if($anidesp) {
                $anidesp->animal_id = $data['animal_id'];
                $anidesp->dewormer_id = $data['dewormer_id'];
                $anidesp->dose = $data['dose'];
                $anidesp->application_date = $data['application_date'];
                $anidesp->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($anidesp, 201);
                } else {
                    return redirect()->route('animaldewormer.show', $anidesp->animal_id)->with('info', 'Aplicación actualizada con éxito');
                }
            } else {
                return response()->json(['error' => 'Aplicación de Desparasitante no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AnimalDewormer  $animalDewormer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $anidesp = AnimalDewormer::find($id);
            if($anidesp) {
                $anidesp->delete();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['exitoso' => 'Aplicación eliminada con éxito'], 204);
                } else {
                    return redirect()->route('animaldewormer.show', $anidesp->animal_id)->with('info', 'Aplicación eliminada con éxito');
                }
            } else {
                return response()->json(['error' => 'Aplicación de Desparasitante no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
