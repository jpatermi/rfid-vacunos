<?php

namespace App\Http\Controllers;

use App\Veterinarian;
use Illuminate\Http\Request;

class VeterinarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $veterinarians = Veterinarian::all()->sortBy('name')->values();
        return response()->json($veterinarians, 200);
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
            $veterinarian = Veterinarian::create([
              'name' => $data['name']
            ]);
            return response()->json($veterinarian, 201);
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Veterinarian  $veterinarian
     * @return \Illuminate\Http\Response
     */
    public function show($veterinarian)
    {
        $veterinarian = Veterinarian::find($veterinarian);
        if($veterinarian) {
            return response()->json($veterinarian, 200);
        } else {
            return response()->json(['error' => 'Veterinario no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Veterinarian  $veterinarian
     * @return \Illuminate\Http\Response
     */
    public function edit(Veterinarian $veterinarian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Veterinarian  $veterinarian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $veterinarian)
    {
        try {
            $data = $request->json()->all();
            $veterinarian = Veterinarian::find($veterinarian);
            if($veterinarian) {
                $veterinarian->name = $data['name'];
                $veterinarian->save();
                return response()->json($veterinarian, 201);
            } else {
                return response()->json(['error' => 'Veterinario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Veterinarian  $veterinarian
     * @return \Illuminate\Http\Response
     */
    public function destroy($veterinarian)
    {
        try {
            $veterinarianLocated = Veterinarian::find($veterinarian);
            if($veterinarianLocated) {
                $medicalDiagnostics = MedicalDiagnostic::where('veterinarian_id', $veterinarian)->get();
                if ($medicalDiagnostics->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($medicalDiagnostics as $medicalDiagnostic) {
                        $animalRFID[] = $medicalDiagnostic->id;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $veterinarianLocated->delete();
                    return response()->json(['exitoso' => 'Veterinario: ' . $veterinarianLocated->name . ' eliminado con éxito'], 204);
                }
            } else {
                return response()->json(['error' => 'Veterinario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
