<?php

namespace App\Http\Controllers;

use App\Diagnostic;
use Illuminate\Http\Request;

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
        return response()->json($diagnostics, 200);
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
            $diagnostic = Diagnostic::create([
              'name' => $data['name']
            ]);
            return response()->json($diagnostic, 201);
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
    public function edit(Diagnostic $diagnostic)
    {
        //
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
            $data = $request->json()->all();
            $diagnostic = Diagnostic::find($diagnostic);
            if($diagnostic) {
                $diagnostic->name = $data['name'];
                $diagnostic->save();
                return response()->json($diagnostic, 201);
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
                $medicalDiagnostics = MedicalDiagnostic::where('diagnostic_id', $diagnostic)->get();
                if ($medicalDiagnostics->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($medicalDiagnostics as $medicalDiagnostic) {
                        $animalRFID[] = $medicalDiagnostic->id;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $diagnosticLocated->delete();
                    return response()->json(['exitoso' => 'Diagnóstico: ' . $diagnosticLocated->name . ' eliminado con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Diagnóstico no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
