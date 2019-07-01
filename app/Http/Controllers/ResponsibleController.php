<?php

namespace App\Http\Controllers;

use App\Responsible;
use Illuminate\Http\Request;
use App\Disease;

class ResponsibleController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:responsibles.index')->only('index');
        $this->middleware('permission:responsibles.create')->only(['create', 'store']);
        $this->middleware('permission:responsibles.show')->only('show');
        $this->middleware('permission:responsibles.edit')->only(['edit', 'update']);
        $this->middleware('permission:responsibles.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responsibles = Responsible::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($responsibles, 200);
        } else {
            $varGenerals = $responsibles;
            $labelGeneral = 'Responsable';
            $model = 'responsibles';
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
        $labelGeneral = 'Responsable';
        $model = 'responsibles';
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
            $responsible = Responsible::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($responsible, 201);
            } else {
                return redirect()->route('responsibles.edit', $responsible->id)->with('info', 'Responsable guardado con éxito');
            }
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
    public function edit($responsible)
    {
        $varGeneral = Responsible::find($responsible);
        $labelGeneral = 'Responsable';
        $model = 'responsibles';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
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
            $data = $request->validate([
                'name' => 'required'
            ]);
            $responsible = Responsible::find($responsible);
            if($responsible) {
                $responsible->name = $data['name'];
                $responsible->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($responsible, 201);
                } else {
                    return redirect()->route('responsibles.edit', $responsible->id)->with('info', 'Responsable actualizado con éxito');
                }
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
                $diseases = Disease::where('responsible_id', $responsible)->get();
                if ($diseases->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($diseases as $disease) {
                        $animalRFID[] = $disease->animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $responsibleLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Responsable: ' . $responsibleLocated->name . ' eliminado con éxito'], 204);
                    } else {
                        return redirect()->route('responsibles.index')->with('info', 'Responsable eliminado con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Responsable no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
