<?php

namespace App\Http\Controllers;

use App\Diagnostic;
use Illuminate\Http\Request;
use App\Disease;

class DiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diagnostics = Diagnostic::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($diagnostics, 200);
        } else {
            $varGenerals = $diagnostics;
            $labelGeneral = 'Diagnóstico';
            $model = 'diagnostics';
            return view('configuration.general.indexGeneral', compact('varGenerals', 'labelGeneral', 'model'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $labelGeneral = 'Diagnóstico';
        $model = 'diagnostics';
        return view('configuration.general.createGeneral', compact('labelGeneral', 'model'));
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
                'name' => 'required'
            ]);
            $diagnostic = Diagnostic::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($diagnostic, 201);
            } else {
                return redirect()->route('diagnostics.index');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function show($diagnostic)
    {
        $diagnostic = Diagnostic::find($diagnostic);
        if($diagnostic) {
            return response()->json($diagnostic, 200);
        } else {
            return response()->json(['error' => 'diagnóstico no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function edit($diagnostic)
    {
        $varGeneral = Diagnostic::find($diagnostic);
        $labelGeneral = 'Diagnóstico';
        $model = 'diagnostics';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $diagnostic)
    {
        try {
            $data = $request->validate([
                'name' => 'required'
            ]);
            $diagnostic = Diagnostic::find($diagnostic);
            if($diagnostic) {
                $diagnostic->name = $data['name'];
                $diagnostic->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($diagnostic, 201);
                } else {
                    return redirect()->route('diagnostics.index');
                }
            } else {
                return response()->json(['error' => 'diagnóstico no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diagnostic  $diagnostic
     * @return \Illuminate\Http\Response
     */
    public function destroy($diagnostic)
    {
        try {
            $diagnosticLocated = Diagnostic::find($diagnostic);
            if($diagnosticLocated) {
                $diseases = Disease::where('diagnostic_id', $diagnostic)->get();
                if ($diseases->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($diseases as $disease) {
                        $disease->animal;
                        $animalRFID[] = $disease->animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $diagnosticLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Diagnóstico: ' . $diagnosticLocated->name . ' eliminado con éxito'], 204);
                    } else {
                        return redirect()->route('diagnostics.index');
                    }
                }
            } else {
                return response()->json(['error' => 'Diagnóstico no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
