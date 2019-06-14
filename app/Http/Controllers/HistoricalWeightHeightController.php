<?php

namespace App\Http\Controllers;

use App\HistoricalWeightHeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Animal;

class HistoricalWeightHeightController extends Controller
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
        $animal_id = $request->all();
        $animal = Animal::find($animal_id)->first();
        return view('historicals.createHistorical', compact('animal'));
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
            global $data, $animalHistoricals, $animal;
            $data = $request->validate([
              'animal_id'        => 'required',
              'weight'           => 'required|numeric',
              'height'           => 'required|numeric',
              'measurement_date' => 'required|date',
            ]);
            $animal = Animal::find($data['animal_id']);
            if($animal) {
                /*** Se verifica si se ha tomado la misma medida antes ***/
                $animalHistoricals = $animal->animalHistoricals()->where('animal_id', $data['animal_id'])
                                       ->where('weight', $data['weight'])
                                       ->where('height', $data['height'])
                                       ->get()
                                       ->first();
                if(!$animalHistoricals) {
                   DB::transaction(function ()
                   {
                        global $data, $animalHistoricals, $animal;
                        $animalHistoricals = HistoricalWeightHeight::create([
                          'animal_id' => $data['animal_id'],
                          'weight' => $data['weight'],
                          'height' => $data['height'],
                          'measurement_date' => $data['measurement_date'],
                        ]);
                        /*** Se actualiza el último peso y altura en el Animal ***/
                        $last_historical = $animal->animalHistoricals->sortBy('measurement_date')->last();
                        $animal->last_weight = $last_historical->weight;
                        $animal->last_height = $last_historical->height;
                        $animal->save();
                    });
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json($animalHistoricals, 201);
                    } else {
                        return redirect()->route('historicals.show', $animal->id);
                    }
                } else {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene registrado el Peso: ' . $animalHistoricals->weight . ' y la Altura: ' . $animalHistoricals->weight . ' de Fecha: ' . $animalHistoricals->measurement_date->format('d/m/Y')], 406);
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
     * @param  \App\HistoricalWeightHeight  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $animalHistoricals = $animal->animalHistoricals()
                                   ->orderBy('measurement_date', 'desc')
                                   ->get();
            if($animalHistoricals->isNotEmpty())
            {
                $applications = array();
                foreach ($animalHistoricals as $animalHistorical)
                {
                    $line = $animalHistorical->measurement_date->format('d/m/Y');
                    for ($i=0; $i < 5; $i++) {
                        $line = $line . "\t";
                    }
                    $line = $line . $animalHistorical->weight;
                    for ($i=0; $i < 5; $i++) {
                        $line = $line . "\t";
                    }
                    $line = $line . $animalHistorical->height;
                    $application = array('id' => $animalHistorical->id,
                                         'weight' => $animalHistorical->weight,
                                         'height' => $animalHistorical->height,
                                         'measurement_date' => $animalHistorical->measurement_date->format('d/m/Y'),
                                         'line' => $line);
                    $applications[] = $application;
                }
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($applications, 200);
                } else {
                    return view('historicals.showHistorical', compact('animal', 'applications'));
                }
            }
            else
            {
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no tiene Histórico aún'], 406);
                } else {
                    $applications = '';
                    return view('historicals.showHistorical', compact('animal', 'applications'));
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
     * @param  \App\HistoricalWeightHeight  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function edit(HistoricalWeightHeight $historical)
    {
        $animal = Animal::find($historical->animal_id);
        $historical = HistoricalWeightHeight::find($historical->id);
        return view('historicals.editHistorical', compact('animal', 'historical'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HistoricalWeightHeight  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            global $data, $animalHistoricals;
            $data = $request->validate([
              'animal_id'        => 'required',
              'weight'           => 'required|numeric',
              'height'           => 'required|numeric',
              'measurement_date' => 'required|date',
            ]);
            $animalHistoricals = HistoricalWeightHeight::find($id);
            if($animalHistoricals)
            {
               DB::transaction(function ()
               {
                    global $data, $animalHistoricals;
                    $animalHistoricals->animal_id = $data['animal_id'];
                    $animalHistoricals->weight = $data['weight'];
                    $animalHistoricals->height = $data['height'];
                    $animalHistoricals->measurement_date = $data['measurement_date'];
                    $animalHistoricals->save();
                    /*** Se actualiza en el Animal el último Peso y Altura ***/
                    $animal = Animal::find($animalHistoricals->animal_id);
                    $last_historical = $animal->animalHistoricals->sortBy('measurement_date')->last();
                    $animal->last_weight = $last_historical->weight;
                    $animal->last_height = $last_historical->height;
                    $animal->save();
                });
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($animalHistoricals, 201);
                } else {
                    return redirect()->route('historicals.show', $animalHistoricals->animal_id);
                }
            } else {
                return response()->json(['error' => 'Histórico de Peso y Altura no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HistoricalWeightHeight  $animalVitamin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $animalHistoricals = HistoricalWeightHeight::find($id);
            if($animalHistoricals) {
                $animalHistoricals->delete();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['exitoso' => 'Histórico eliminado con éxito'], 204);
                } else {
                    return redirect()->route('historicals.show', $animalHistoricals->animal_id);
                }
            } else {
                return response()->json(['error' => 'Histórico de Peso y Altura no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
