<?php

namespace App\Http\Controllers;

use App\Responsible;
use Illuminate\Http\Request;

class ResponsibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responsibles = Responsible::all()->sortBy('name')->values();
        return response()->json($responsibles, 200);
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
            $responsible = Responsible::create([
              'name' => $data['name']
            ]);
            return response()->json($responsible, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Responsible  $responsible
     * @return \Illuminate\Http\Response
     */
    public function show($responsible)
    {
        $responsible = Responsible::find($responsible);
        if($responsible) {
            return response()->json($responsible, 200);
        } else {
            return response()->json(['error' => 'Responsable no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Responsible  $responsible
     * @return \Illuminate\Http\Response
     */
    public function edit(Responsible $responsible)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Responsible  $responsible
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $responsible)
    {
        try {
            $data = $request->json()->all();
            $responsible = Responsible::find($responsible);
            if($responsible) {
                $responsible->name = $data['name'];
                $responsible->save();
                return response()->json($responsible, 201);
            } else {
                return response()->json(['error' => 'Responsable no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Responsible  $responsible
     * @return \Illuminate\Http\Response
     */
    public function destroy($responsible)
    {
        try {
            $responsibleLocated = Responsible::find($responsible);
            if($responsibleLocated) {
                $medicalDiagnostics = MedicalDiagnostic::where('responsible_id', $responsible)->get();
                if ($medicalDiagnostics->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($medicalDiagnostics as $medicalDiagnostic) {
                        $animalRFID[] = $medicalDiagnostic->id;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $responsibleLocated->delete();
                    return response()->json(['exitoso' => 'Responsable: ' . $responsibleLocated->name . ' eliminado con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Responsable no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
