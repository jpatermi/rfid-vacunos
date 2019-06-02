<?php

namespace App\Http\Controllers;

use App\AnimalExamn;
use Illuminate\Http\Request;
use App\Animal;

class AnimalExamnController extends Controller
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
                $aniexam = $animal->examns()->where('animal_id', $data['animal_id'])
                                  ->where('examn_id', $data['examn_id'])
                                  ->where('application_date', $data['application_date'])
                                  ->get()
                                  ->first();
                if(!$aniexam) {
                    $aniexam = AnimalExamn::create([
                      'animal_id' => $data['animal_id'],
                      'examn_id' => $data['examn_id'],
                      'application_date' => $data['application_date'],
                    ]);
                    return response()->json($aniexam, 201);
                } else {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene el Examen: ' . $aniexam->name . ' de fecha: ' . $aniexam->pivot->application_date->format('d/m/Y')], 406);
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
     * @param  \App\AnimalExamn  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $examns = $animal->examns()
                             ->orderBy('application_date', 'desc')
                             ->orderBy('name', 'asc')
                             ->get();
            if($examns->isNotEmpty())
            {
                $applications = array();
                foreach ($examns as $examn)
                {
                    $application = array('id_vac_desp_vit' => $examn->id,
                                         'name_vac_desp_vit' => $examn->name,
                                         'dose' => null,
                                         'application_date' => $examn->pivot->application_date->format('d/m/Y'),
                                         'id_ani_vac_desp_vit' => $examn->pivot->id);
                    $applications[] = $application;
                }
                return response()->json($applications, 200);
            }
            else
            {
                return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no tiene Exámenes aún'], 406);
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
     * @param  \App\AnimalExamn  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function edit(AnimalExamn $animalVitamin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AnimalExamn  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->json()->all();
            $aniexam = AnimalExamn::find($id);
            if($aniexam) {
                $aniexam->animal_id = $data['animal_id'];
                $aniexam->examn_id = $data['examn_id'];
                $aniexam->application_date = $data['application_date'];
                $aniexam->save();
                return response()->json($aniexam, 201);
            } else {
                return response()->json(['error' => 'Aplicación de Examen no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AnimalExamn  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $aniexam = AnimalExamn::find($id);
            if($aniexam) {
                $aniexam->delete();
                return response()->json(['exitoso' => 'Aplicación eliminada con éxito'], 204);
            } else {
                return response()->json(['error' => 'Aplicación de Examen no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
