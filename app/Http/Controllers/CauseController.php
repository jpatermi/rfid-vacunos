<?php

namespace App\Http\Controllers;

use App\Cause;
use Illuminate\Http\Request;
use App\Disease;

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
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($causes, 200);
        } else {
            $varGenerals = $causes;
            $labelGeneral = 'Causa';
            $model = 'causes';
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
        $labelGeneral = 'Causa';
        $model = 'causes';
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
            $cause = Cause::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($cause, 201);
            } else {
                return redirect()->route('causes.index');
            }
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
    public function edit($cause)
    {
        $varGeneral = Cause::find($cause);
        $labelGeneral = 'Causa';
        $model = 'causes';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
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
            $data = $request->validate([
                'name' => 'required'
            ]);
            $cause = Cause::find($cause);
            if($cause) {
                $cause->name = $data['name'];
                $cause->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($cause, 201);
                } else {
                    return redirect()->route('causes.index');
                }
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
                $diseases = Disease::where('cause_id', $cause)->get();
                if ($diseases->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($diseases as $disease) {
                        $animalRFID[] = $disease->animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $causeLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Causa: ' . $causeLocated->name . ' eliminada con éxito'], 204);
                    } else {
                        return redirect()->route('causes.index');
                    }
                }
            } else {
                return response()->json(['error' => 'Causa no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
