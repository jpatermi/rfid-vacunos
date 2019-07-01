<?php

namespace App\Http\Controllers;

use App\Veterinarian;
use Illuminate\Http\Request;
use App\Disease;

class VeterinarianController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:veterinarians.index')->only('index');
        $this->middleware('permission:veterinarians.create')->only(['create', 'store']);
        $this->middleware('permission:veterinarians.show')->only('show');
        $this->middleware('permission:veterinarians.edit')->only(['edit', 'update']);
        $this->middleware('permission:veterinarians.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $veterinarians = Veterinarian::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($veterinarians, 200);
        } else {
            $varGenerals = $veterinarians;
            $labelGeneral = 'Veterinario';
            $model = 'veterinarians';
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
        $labelGeneral = 'Veterinario';
        $model = 'veterinarians';
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
            $veterinarian = Veterinarian::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($veterinarian, 201);
            } else {
                return redirect()->route('veterinarians.edit', $veterinarian->id)->with('info', 'Veterinario guardado con éxito');
            }
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
    public function edit($veterinarian)
    {
        $varGeneral = Veterinarian::find($veterinarian);
        $labelGeneral = 'Veterinario';
        $model = 'veterinarians';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
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
            $data = $request->validate([
                'name' => 'required'
            ]);
            $veterinarian = Veterinarian::find($veterinarian);
            if($veterinarian) {
                $veterinarian->name = $data['name'];
                $veterinarian->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($veterinarian, 201);
                } else {
                    return redirect()->route('veterinarians.edit', $veterinarian->id)->with('info', 'Veterinario actualizado con éxito');
                }
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
                $diseases = Disease::where('veterinarian_id', $veterinarian)->get();
                if ($diseases->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($diseases as $disease) {
                        $animalRFID[] = $disease->animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $veterinarianLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Veterinario: ' . $veterinarianLocated->name . ' eliminado con éxito'], 204);
                    } else {
                        return redirect()->route('veterinarians.index')->with('info', 'Veterinario eliminado con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Veterinario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
