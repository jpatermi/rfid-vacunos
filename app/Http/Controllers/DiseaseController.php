<?php

namespace App\Http\Controllers;

use App\Disease;
use Illuminate\Http\Request;
use App\Animal;
use App\DiseaseTreatment;
use Illuminate\Support\Facades\DB;

class DiseaseController extends Controller
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
            global $data, $disease;
            $data = $request->json()->all();
            $animal = Animal::find($data['animal_id']);
            if($animal) {
                $disease = $animal->diseases()->where('animal_id', $data['animal_id'])
                                           ->where('diagnostic_id', $data['diagnostic_id'])
                                           ->where('review_date', $data['review_date'])
                                           ->get()
                                           ->first();
                if(!$disease) {
                    $dataTreatments = collect($data['treatment']);
                    /*** Se verifica que no hayan Tratamientos repetidos ***/
                    if($dataTreatments->groupBy('treatment_id')->count() == $dataTreatments->count())
                    {
                        DB::transaction(function ()
                        {
                            global $data, $disease;
                            $disease = Disease::create([
                              'animal_id' => $data['animal_id'],
                              'veterinarian_id' => $data['veterinarian_id'],
                              'diagnostic_id' => $data['diagnostic_id'],
                              'cause_id' => $data['cause_id'],
                              'responsible_id' => $data['responsible_id'],
                              'review_date' => $data['review_date'],
                            ]);
                            /*** Se procede a crear los tratamientos para la Enfermedad ***/
                            $dataTreatments = $data['treatment'];
                            foreach ($dataTreatments as $dataTreatment) {
                                DiseaseTreatment::create([
                                  'disease_id' => $disease->id,
                                  'treatment_id' => $dataTreatment['treatment_id'],
                                  'indication' => $dataTreatment['indication'],
                                ]);
                            }
                            $disease->treatments;
                        });
                        return response()->json($disease, 201);
                    }
                    else
                    {
                        return response()->json(['error' => 'Existen Tratamientos repetidos. ¡Revise por favor!'], 406);
                    }
                }
                else
                {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene el Diagnóstico: ' . $disease->diagnostic->name. ' de fecha: ' . $disease->review_date->format('d/m/Y')], 406);
                }
            }
            else
            {
                return response()->json(['error' => 'Animal no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disease  $disease
     * @return \Illuminate\Http\Response
     */
    public function showV1($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $diseases = $animal->diseases()->orderBy('review_date', 'desc')->get();
            if($diseases->isNotEmpty())
            {
                $animalDiseases = array();
                foreach ($diseases as $disease)
                {
                    $treatments = $disease->treatments;
                    $arrTreatments = array();
                    foreach ($treatments as $treatment) {
                        $arrTreatment = array('id' => $treatment->id,
                                              'name' => $treatment->name,
                                              'indication' => $treatment->pivot->indication);
                        $arrTreatments[] = $arrTreatment;
                    }
                    $animalDisease = array('id' => $disease->id,
                                      'animal_id' => $disease->animal_id,
                                      'veterinarian_id' => $disease->veterinarian_id,
                                      'diagnostic_id' => $disease->diagnostic_id,
                                      'diagnostic_name' => $disease->diagnostic->name,
                                      'cause_id' => $disease->cause_id,
                                      'responsible_id' => $disease->responsible_id,
                                      'review_date' => $disease->review_date->format('d/m/Y'),
                                      'treatments' => $arrTreatments);
                    $animalDiseases[] = $animalDisease;
                }
                return response()->json($animalDiseases, 200);
            }
            else
            {
                return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha tenido eventos de Enfermedad aún'], 406);
            }
        } else {
            return response()->json(['error' => 'Animal no existente'], 406);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disease  $disease
     * @return \Illuminate\Http\Response
     */
    public function show($disease)
    {
        $disease = Disease::find($disease);
        if($disease)
        {
            $disease->treatments;
            return response()->json($disease, 200);
        }
        else
        {
            return response()->json(['error' => 'Enfermedad no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disease  $disease
     * @return \Illuminate\Http\Response
     */
    public function edit(Disease $disease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disease  $disease
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $disease)
    {
        global $data, $diseaseLocated, $disease_id, $dataTreatments;

        $disease_id = $disease;

        $diseaseLocated = Disease::find($disease);
        $update_at = $diseaseLocated->update_at;
        if($diseaseLocated)
        {
            $data = $request->json()->all();
            $dataTreatments = collect($data['treatment']);
            /*** Se verifica que no hayan Tratamientos repetidos ***/
            if($dataTreatments->groupBy('treatment_id')->count() == $dataTreatments->count())
            {
                DB::transaction(function ()
                {
                    global $data, $diseaseLocated, $disease_id, $dataTreatments;

                    $diseaseLocated->animal_id = $data['animal_id'];
                    $diseaseLocated->veterinarian_id = $data['veterinarian_id'];
                    $diseaseLocated->diagnostic_id = $data['diagnostic_id'];
                    $diseaseLocated->cause_id = $data['cause_id'];
                    $diseaseLocated->responsible_id = $data['responsible_id'];
                    $diseaseLocated->review_date = $data['review_date'];
                    $diseaseLocated->save();
                    /*** Se procede a Actualizar los Tratamientos para la Enfermedad ***/
                    $diseaseTreatments = DiseaseTreatment::where('disease_id', $disease_id)->get();
                    if ($dataTreatments->count() == $diseaseTreatments->count()) 
                    {
                      $arrTreatments = $data['treatment'];
                      $i = 0;
                      $distintc = false;
                      foreach ($diseaseTreatments as $diseaseTreatment)
                      {
                          /*** Se verifica si hubo algún cambio ***/
                          if (($diseaseTreatment->treatment_id != $arrTreatments[$i]['treatment_id'])
                              or ($diseaseTreatment->indication != $arrTreatments[$i]['indication']))
                          {
                              $distintc = true;
                          }
                          $i++;
                      }
                    }
                    else
                    {
                      $distintc = true;
                    }
                    if ($distintc)
                    {
                        DiseaseTreatment::where('disease_id', $disease_id)->delete();
                        $dataTreatments = $data['treatment'];
                        foreach ($dataTreatments as $dataTreatment)
                        {
                            DiseaseTreatment::create([
                              'disease_id' => $diseaseLocated->id,
                              'treatment_id' => $dataTreatment['treatment_id'],
                              'indication' => $dataTreatment['indication'],
                            ]);
                        }
                    }
                    $diseaseLocated->treatments;
                });
                return response()->json($diseaseLocated, 201);
            }
            else
            {
                return response()->json(['error' => 'Existen Tratamientos repetidos. ¡Revise por favor!'], 406);
            }
        }
        else
        {
            return response()->json(['error' => 'Enfermedad no existente' . $disease], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Disease  $disease
     * @return \Illuminate\Http\Response
     */
    public function destroy($disease)
    {
        $disease = Disease::find($disease);
        if($disease)
        {
            /*** Se Eliminan también los Tratamientos de la Enfermedad a eliminar ***/
            $treatments = $disease->treatments;
            foreach ($treatments as $treatment)
            {
                $treatment->pivot->delete();
            }
            $disease->delete();
            return response()->json(['exitoso' => 'Enfermedad eliminada con éxito'], 204);
        }
        else
        {
            return response()->json(['error' => 'Enfermedad no existente'], 406);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Disease  $disease
     * @return \Illuminate\Http\Response
     */
    public function getAnimalDiseases($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $diseases = $animal->diseases()->orderBy('review_date', 'desc')->get();
            if($diseases->isNotEmpty())
            {
                $animalDiseases = array();
                foreach ($diseases as $disease)
                {
                    $animalDisease = array('id' => $disease->id,
                                      'diagnostic_name' => $disease->diagnostic->name,
                                      'review_date' => $disease->review_date->format('d/m/Y'));
                    $animalDiseases[] = $animalDisease;
                }
                return response()->json($animalDiseases, 200);
            }
            else
            {
                return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no ha tenido eventos de Enfermedad aún'], 406);
            }
        } else {
            return response()->json(['error' => 'Animal no existente'], 406);
        }
    }
}
