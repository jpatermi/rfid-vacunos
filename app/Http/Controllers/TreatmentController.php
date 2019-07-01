<?php

namespace App\Http\Controllers;

use App\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:treatments.index')->only('index');
        $this->middleware('permission:treatments.create')->only(['create', 'store']);
        $this->middleware('permission:treatments.show')->only('show');
        $this->middleware('permission:treatments.edit')->only(['edit', 'update']);
        $this->middleware('permission:treatments.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $treatments = Treatment::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($treatments, 200);
        } else {
            $varGenerals = $treatments;
            $labelGeneral = 'Tratamiento';
            $model = 'treatments';
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
        $labelGeneral = 'Tratamiento';
        $model = 'treatments';
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
            $treatment = Treatment::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($treatment, 201);
            } else {
                return redirect()->route('treatments.edit', $treatment->id)->with('info', 'Tratamiento guardado con éxito');
            }
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
    public function edit($treatment)
    {
        $varGeneral = Treatment::find($treatment);
        $labelGeneral = 'Tratamiento';
        $model = 'treatments';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
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
            $data = $request->validate([
                'name' => 'required'
            ]);
            $treatment = Treatment::find($treatment);
            if($treatment) {
                $treatment->name = $data['name'];
                $treatment->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($treatment, 201);
                } else {
                    return redirect()->route('treatments.edit', $treatment->id)->with('info', 'Tratamiento actualizado con éxito');
                }
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
                $diseases = $treatmentLocated->diseases;
                if ($diseases->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($diseases as $disease) {
                        $animalRFID[] = $disease->pivot->disease->animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $treatmentLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Tratamiento: ' . $treatmentLocated->name . ' eliminado con éxito'], 204);
                    } else {
                        return redirect()->route('treatments.index')->with('info', 'Tratamiento eliminado con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Tratamiento no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
