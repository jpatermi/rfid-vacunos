<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AnimalVaccination;
use App\Animal;
use App\Vaccination;

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
    public function create(Request $request)
    {
/*        $animal_id = $request->all();
        $animal = Animal::find($animal_id)->first();
        $vaccinations = Vaccination::orderBy('name')->get();
        //dd($animal, $vaccinations);
        $model = 'animalvaccination';
        $title = 'Aplicación de Vacuna';
        return view('animalVaccinations.createAnimalVaccination', compact('animal', 'model', 'title', 'vaccinations'));
*/    }

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
                'animal_id' => 'required',
                'vaccination_id' => 'required',
                'dose' => 'required|numeric',
                'application_date' => 'required|date',
            ]);
            $animal = Animal::find($data['animal_id']);
            if($animal) {
                $anivac = $animal->vaccinations()
                                 ->where('animal_id', $data['animal_id'])
                                 ->where('vaccination_id', $data['vaccination_id'])
                                 ->where('application_date', $data['application_date'])
                                 ->get()
                                 ->first();
                if(!$anivac) {
                    $anivac = AnimalVaccination::create([
                      'animal_id' => $data['animal_id'],
                      'vaccination_id' => $data['vaccination_id'],
                      'dose' => $data['dose'],
                      'application_date' => $data['application_date'],
                    ]);
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json($anivac, 201);
                    } else {
                        return redirect()->route('animalvaccination.show', $anivac->animal_id);
                    }
                } else {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene la vacuna: ' . $anivac->name . ' de fecha: ' . $anivac->pivot->application_date->format('d/m/Y')], 406);
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
    public function showUnSoloListView($animal_id)
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
                    for ($x = 1; $x <= (25 - strlen($vaccination->name)); $x++) {
                        $name = $name . ' ';
                    }
                    $name = $name . $vaccination->pivot->dose;
                    $name2 = $name;
                    for ($x = 1; $x <= (38 - strlen($name2)); $x++) {
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
     * Display the specified resource.
     *
     * @param  int  $animal_id
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $vaccinationsAnimal = $animal->vaccinations()
                                   ->orderBy('application_date', 'desc')
                                   ->orderBy('name', 'asc')
                                   ->get();
            if($vaccinationsAnimal->isNotEmpty())
            {
                $applications = array();
                foreach ($vaccinationsAnimal as $vaccination)
                {
                    $application = array('id_vac_desp_vit' => $vaccination->id,
                                         'name_vac_desp_vit' => $vaccination->name,
                                         'dose' => $vaccination->pivot->dose,
                                         'application_date' => $vaccination->pivot->application_date->format('d/m/Y'),
                                         'id_ani_vac_desp_vit' => $vaccination->pivot->id);
                    $applications[] = $application;
                }
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json($applications, 200);
                }
                else
                {
                    $vaccinations = Vaccination::orderBy('name')->get();
                    //$applications = $vaccinationsAnimal;
                    $model        = 'animalvaccination';
                    $title        = 'Vacunas aplicadas';
                    $label         = 'Vacuna';
                    $nomCampoVacDewVit = 'vaccination_id';
                    return view('animalVacDewVit.showAnimalVacDewVit', compact('applications', 'animal', 'model', 'title', 'vaccinations', 'label', 'nomCampoVacDewVit'));
                }
            }
            else
            {
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha sido vacunado aún'], 406);
                }
                else
                {
                    $vaccinations = Vaccination::orderBy('name')->get();
                    $applications = '';
                    $model        = 'animalvaccination';
                    $title        = 'Vacunas aplicadas';
                    $label         = 'Vacuna';
                    $nomCampoVacDewVit = 'vaccination_id';
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd($id);
        //   $application = AnimalVaccination::find($id);
        // return view('animalVaccinations.editAnimalVaccination', compact('application'));
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
            $data = $request->validate([
                'animal_id' => 'required',
                'vaccination_id' => 'required',
                'dose' => 'required|numeric',
                'application_date' => 'required|date',
            ]);
            $anivac = AnimalVaccination::find($id);
            if($anivac) {
                $anivac->animal_id = $data['animal_id'];
                $anivac->vaccination_id = $data['vaccination_id'];
                $anivac->dose = $data['dose'];
                $anivac->application_date = $data['application_date'];
                $anivac->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($anivac, 201);
                } else {
                    return redirect()->route('animalvaccination.show', $anivac->animal_id);
                }
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
            $anivac = AnimalVaccination::find($id);
            if($anivac) {
                $anivac->delete();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['exitoso' => 'Aplicación eliminada con éxito'], 204);
                } else {
                    return redirect()->route('animalvaccination.show', $anivac->animal_id);
                }
            } else {
                return response()->json(['error' => 'Aplicación de Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
