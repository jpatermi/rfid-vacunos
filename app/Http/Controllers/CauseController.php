<?php

namespace App\Http\Controllers;

use App\Cause;
use Illuminate\Http\Request;

class CauseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $causes = Cause::all()->sortBy('name')->values();
        return response()->json($causes, 200);
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
            $cause = Cause::create([
              'name' => $data['name']
            ]);
            return response()->json($cause, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cause  $cause
     * @return \Illuminate\Http\Response
     */
    public function show($cause)
    {
        $cause = Cause::find($cause);
        if($cause) {
            return response()->json($cause, 200);
        } else {
            return response()->json(['error' => 'Causa no existente'], 406); }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cause  $cause
     * @return \Illuminate\Http\Response
     */
    public function edit(Cause $cause)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cause  $cause
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cause)
    {
        try {
            $data = $request->json()->all();
            $cause = Cause::find($cause);
            if($cause) {
                $cause->name = $data['name'];
                $cause->save();
                return response()->json($cause, 201);
            } else {
                return response()->json(['error' => 'Causa no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cause  $cause
     * @return \Illuminate\Http\Response
     */
    public function destroy($cause)
    {
        try {
            $causeLocated = Cause::find($cause);
            if($causeLocated) {
                $medicalDiagnostics = MedicalDiagnostic::where('cause_id', $cause)->get();
                if ($medicalDiagnostics->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($medicalDiagnostics as $medicalDiagnostic) {
                        $animalRFID[] = $medicalDiagnostic->id;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $causeLocated->delete();
                    return response()->json(['exitoso' => 'Causa: ' . $causeLocated->name . ' eliminada con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Causa no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
