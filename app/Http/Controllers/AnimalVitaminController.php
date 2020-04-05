<?php

namespace App\Http\Controllers;

use App\AnimalVitamin;
use Illuminate\Http\Request;
use App\Animal;
use App\Vitamin;
use App\AgeGroup;

class AnimalVitaminController extends Controller
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
                'vitamin_id'       => 'required',
                'dose'             => 'required|numeric',
                'application_date' => 'required|date',
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                $animal = Animal::find($data['animal_id']);
                if($animal) {
                    $anivit = $animal->vitamins()->where('animal_id', $data['animal_id'])
                                     ->where('vitamin_id', $data['vitamin_id'])
                                     ->where('application_date', $data['application_date'])
                                     ->get()
                                     ->first();
                    if(!$anivit) {
                        $anivit = AnimalVitamin::create([
                          'animal_id' => $data['animal_id'],
                          'vitamin_id' => $data['vitamin_id'],
                          'dose' => $data['dose'],
                          'application_date' => $data['application_date'],
                        ]);
                        if (request()->header('Content-Type') == 'application/json') {
                            return response()->json($anivit, 201);
                        } else {
                            return redirect()->route('animalvitamin.show', $anivit->animal_id)->with('info', 'Aplicación guardada con éxito');
                        }
                    } else {
                        return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene la Vitamina: ' . $anivit->name . ' de fecha: ' . $anivit->pivot->application_date->format('d/m/Y')], 406);
                    }
                } else {
                    return response()->json(['error' => 'Animal no existente'], 406);
                }
            } else {
                foreach ($data['animal_id'] as $id) {
                    $animal = Animal::find($id);
                    if(!$animal) {
                        return response()->json(['error' => 'Animal no existente'], 406);
                    }
                    $anivit = $animal->vitamins()->where('animal_id', $data['animal_id'])
                                     ->where('vitamin_id', $data['vitamin_id'])
                                     ->where('application_date', $data['application_date'])
                                     ->get()
                                     ->first();
                    if($anivit) {
                        return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene la Vitamina: ' . $anivit->name . ' de fecha: ' . $anivit->pivot->application_date->format('d/m/Y')], 406);
                    }
                }
                foreach ($data['animal_id'] as $id) {
                    $anivit = AnimalVitamin::create([
                      'animal_id' => $id,
                      'vitamin_id' => $data['vitamin_id'],
                      'dose' => $data['dose'],
                      'application_date' => $data['application_date'],
                    ]);
                }
                if (count($data['animal_id']) > 1) {
                    return redirect()->route('animals.index')->with('info', 'Aplicación Masiva guardada con éxito');
                } else {
                    return redirect()->route('animalvitamin.show', $anivit->animal_id)->with('info', 'Aplicación guardada con éxito');
                }
            }

        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AnimalVitamin  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $vitamins = $animal->vitamins()
                               ->orderBy('application_date', 'desc')
                               ->orderBy('name', 'asc')
                               ->get();
            if($vitamins->isNotEmpty())
            {
                $applications = array();
                foreach ($vitamins as $vitamin)
                {
                    $application = array('id_vac_desp_vit'      => $vitamin->id,
                                         'name_vac_desp_vit'    => $vitamin->name,
                                         'dose'                 => $vitamin->pivot->dose,
                                         'lot'                  => $vitamin->lot,
                                         'application_date'     => $vitamin->pivot->application_date->format('d/m/Y'),
                                         'id_ani_vac_desp_vit'  => $vitamin->pivot->id);
                    $applications[] = $application;
                }
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json($applications, 200);
                }
                else
                {
                    $vaccinations = Vitamin::orderBy('name')->get();
                    //$applications = $dewormersAnimal;
                    $model        = 'animalvitamin';
                    $title        = 'Vitaminas aplicadas';
                    $label        = 'Vitamina';
                    $nomCampoVacDewVit = 'vitamin_id';
                    return view('animalVacDewVit.showAnimalVacDewVit', compact('applications', 'animal', 'model', 'title', 'vaccinations', 'label', 'nomCampoVacDewVit'));
                }
            }
            else
            {
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no tiene Vitaminas aún'], 406);
                }
                else
                {
                    $vaccinations = Vitamin::orderBy('name')->get();
                    $applications = '';
                    $model        = 'animalvitamin';
                    $title        = 'Vitaminas aplicadas';
                    $label        = 'Vitamina';
                    $nomCampoVacDewVit = 'vitamin_id';
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
     * @param  \App\AnimalVitamin  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function edit(AnimalVitamin $animalVitamin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AnimalVitamin  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'animal_id'        => 'required',
                'vitamin_id'       => 'required',
                'dose'             => 'required|numeric',
                'application_date' => 'required|date',
            ]);
            $anivit = AnimalVitamin::find($id);
            if($anivit) {
                $anivit->animal_id = $data['animal_id'];
                $anivit->vitamin_id = $data['vitamin_id'];
                $anivit->dose = $data['dose'];
                $anivit->application_date = $data['application_date'];
                $anivit->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($anivit, 201);
                } else {
                    return redirect()->route('animalvitamin.show', $anivit->animal_id)->with('info', 'Aplicación actualizada con éxito');
                }
            } else {
                return response()->json(['error' => 'Aplicación de Vitamina no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AnimalVitamin  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $anivit = AnimalVitamin::find($id);
            if($anivit) {
                $anivit->delete();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['exitoso' => 'Aplicación eliminada con éxito'], 204);
                } else {
                    return redirect()->route('animalvitamin.show', $anivit->animal_id)->with('info', 'Aplicación eliminada con éxito');
                }
            } else {
                return response()->json(['error' => 'Aplicación de Vitamina no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * massive load of vitamins
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulkLoad(Request $request)
    {
        $ageGroups          = AgeGroup::orderBy('name')->get();
        $vaccinations       = Vitamin::orderBy('name')->get();
        $model              = 'animalvitamin';
        $title              = 'Vitaminas aplicadas';
        $label              = 'Vitamina';
        $nomCampoVacDewVit  = 'vitamin_id';
        return view('bulkLoad.showVacDewVit', compact('ageGroups', 'vaccinations', 'model', 'title', 'label', 'nomCampoVacDewVit'));
    }
}
