<?php

namespace App\Http\Controllers;

use App\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $treatments = Treatment::all()->sortBy('name')->values();
        return response()->json($treatments, 200);
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
            $treatment = Treatment::create([
              'name' => $data['name']
            ]);
            return response()->json($treatment, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function show($treatment)
    {
        $treatment = Treatment::find($treatment);
        if($treatment) {
            return response()->json($treatment, 200);
        } else {
            return response()->json(['error' => 'Tratamiento no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function edit(Treatment $treatment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $treatment)
    {
        try {
            $data = $request->json()->all();
            $treatment = Treatment::find($treatment);
            if($treatment) {
                $treatment->name = $data['name'];
                $treatment->save();
                return response()->json($treatment, 201);
            } else {
                return response()->json(['error' => 'Tratamiento no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Treatment  $treatment
     * @return \Illuminate\Http\Response
     */
    public function destroy($treatment)
    {
        try {
            $treatmentLocated = Treatment::find($treatment);
            if($treatmentLocated) {
                $medicalDiagnostics = MedicalDiagnostic::where('treatment_id', $treatment)->get();
                if ($medicalDiagnostics->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($medicalDiagnostics as $medicalDiagnostic) {
                        $animalRFID[] = $medicalDiagnostic->id;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $treatmentLocated->delete();
                    return response()->json(['exitoso' => 'Tratamiento: ' . $treatmentLocated->name . ' eliminado con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Tratamiento no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
