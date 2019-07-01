<?php

namespace App\Http\Controllers;

use App\Production;
use Illuminate\Http\Request;
use App\Animal;

class ProductionController extends Controller
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
        return view('productions.createProduction', compact('animal'));
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
              'animal_id' => 'required',
              'colostrum' => 'required|numeric',
              'milk' => 'required|numeric',
              'production_date' => 'required|date',
            ]);
            $animal = Animal::find($data['animal_id']);
            if($animal) {
                $productions = $animal->productions()->where('animal_id', $data['animal_id'])
                                       ->where('colostrum', $data['colostrum'])
                                       ->where('milk', $data['milk'])
                                       ->get()
                                       ->first();
                if(!$productions) {
                    $productions = Production::create([
                      'animal_id' => $data['animal_id'],
                      'colostrum' => $data['colostrum'],
                      'milk' => $data['milk'],
                      'production_date' => $data['production_date'],
                    ]);
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json($productions, 201);
                    } else {
                        return redirect()->route('productions.edit', $productions->id)->with('info', 'Producción guardada con éxito');
                    }
                } else {
                    return response()->json(['error' => 'El Animal: ' . $animal->animal_rfid . ' ya tiene registrado el Calostro: ' . $productions->colostrum . ' y la Leche: ' . $productions->milk . ' de Fecha: ' . $productions->production_date->format('d/m/Y')], 406);
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
     * @param  \App\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id)
    {
        $animal = Animal::find($animal_id);
        if($animal)
        {
            $productions = $animal->productions()
                                   ->orderBy('production_date', 'desc')
                                   ->get();
            if($productions->isNotEmpty())
            {
                $applications = array();
                foreach ($productions as $production)
                {
                    $line = $production->production_date->format('d/m/Y');
                    for ($i=0; $i < 5; $i++) {
                        $line = $line . "\t";
                    }
                    $line = $line . $production->colostrum;
                    for ($i=0; $i < 5; $i++) {
                        $line = $line . "\t";
                    }
                    $line = $line . $production->milk;
                    $application = array('id' => $production->id,
                                         'colostrum' => $production->colostrum,
                                         'milk' => $production->milk,
                                         'production_date' => $production->production_date->format('d/m/Y'),
                                         'line' => $line);
                    $applications[] = $application;
                }
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($applications, 200);
                } else {
                    return view('/productions/showProduction', compact('animal', 'applications'));
                }
            }
            else
            {
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['error' => 'el Animal ' . $animal->animal_rfid . ' no tiene Producción aún'], 406);
                } else {
                    $applications = '';
                    return view('/productions/showProduction', compact('animal', 'applications'));
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
     * @param  \App\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function edit(Production $production)
    {
        $animal = Animal::find($production->animal_id);
        $production = Production::find($production->id);
        return view('productions.editProduction', compact('animal', 'production'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Production $production)
    {
        try {
            $data = $request->validate([
              'animal_id' => 'required',
              'colostrum' => 'required|numeric',
              'milk' => 'required|numeric',
              'production_date' => 'required|date',
            ]);
            $productions = Production::find($production->id);
            if($productions) {
                $productions->animal_id = $data['animal_id'];
                $productions->colostrum = $data['colostrum'];
                $productions->milk = $data['milk'];
                $productions->production_date = $data['production_date'];
                $productions->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($productions, 201);
                } else {
                    return redirect()->route('productions.edit', $productions->id)->with('info', 'Producción actualizada con éxito');
                }
            } else {
                return response()->json(['error' => 'Producción no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $production = Production::find($id);
            if($production) {
                $production->delete();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json(['exitoso' => 'Producción eliminada con éxito'], 204);
                } else {
                    return redirect()->route('productions.show', $production->animal_id)->with('info', 'Producción eliminada con éxito');
                }
            } else {
                return response()->json(['error' => 'Producción no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
