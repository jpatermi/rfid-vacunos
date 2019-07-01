<?php

namespace App\Http\Controllers;

use App\Lct1;
use Illuminate\Http\Request;
use App\Animal;
use App\Lct2;
use App\Area;

class Lct1Controller extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:lct1s.index')->only('index');
        $this->middleware('permission:lct1s.create')->only(['create', 'store']);
        $this->middleware('permission:lct1s.show')->only('show');
        $this->middleware('permission:lct1s.edit')->only(['edit', 'update']);
        $this->middleware('permission:lct1s.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lct1s= Lct1::orderBy('area_id')->orderBy('name')->get();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($lct1s, 200);
        } else {
            foreach ($lct1s as $lct1) {
                $arrAreaLct1Lct2s[] = array('id'       => $lct1->id,
                                            'name'     => $lct1->name,
                                            'nameSup'  => $lct1->area->name);
            }
            $model = 'lct1s';
            $labelAreaLct1Lct2 = 'Ubicación UNO';
            $labelNameSup = 'Área';
            return view('configuration.AreasLct1sLct2s.indexAreaLct1Lct2', compact('arrAreaLct1Lct2s', 'model', 'labelAreaLct1Lct2', 'labelNameSup'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $varComboSups = Area::all()->sortBy('name');
        $model = 'lct1s';
        $labelAreaLct1Lct2 = 'Ubicación UNO';
        $labelNameSup = 'Área';
        $fieldNameSup = 'area_id';
        return view('configuration.AreasLct1sLct2s.createAreaLct1Lct2', compact('varComboSups', 'model', 'labelAreaLct1Lct2', 'labelNameSup', 'fieldNameSup'));
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
                'name'      => 'required',
                'area_id'   => 'required'
            ]);
            $lct1 = Lct1::create([
              'name' => $data['name'],
              'area_id' => $data['area_id']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($lct1, 201);
            } else {
                return redirect()->route('lct1s.edit', $lct1->id)->with('info', 'Ubicación UNO guardada con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function show($lct1)
    {
        $lct1 = Lct1::find($lct1);
        if($lct1) {
            return response()->json($lct1, 200);
        } else {
            return response()->json(['error' => 'Ubicación 1 no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function edit($lct1)
    {
        $lct1 = Lct1::find($lct1);
        $arrAreaLct1Lct2 = array('id'       => $lct1->id,
                                 'name'     => $lct1->name,
                                 'idSup'    => $lct1->area->id,
                                 'nameSup'  => $lct1->area->name);
        $varComboSups = Area::all()->sortBy('name');
        $model = 'lct1s';
        $labelAreaLct1Lct2 = 'Ubicación UNO';
        $labelNameSup = 'Área';
        $fieldNameSup = 'area_id';
        return view('configuration.AreasLct1sLct2s.editAreaLct1Lct2', compact('varComboSups', 'model', 'labelAreaLct1Lct2', 'labelNameSup', 'arrAreaLct1Lct2', 'fieldNameSup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lct1)
    {
        try {
            $data = $request->validate([
                'name'      => 'required',
                'area_id'   => 'required'
            ]);
            $lct1 = Lct1::find($lct1);
            if($lct1) {
                $lct1->name = $data['name'];
                $lct1->area_id = $data['area_id'];
                $lct1->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($lct1, 201);
                } else {
                    return redirect()->route('lct1s.edit', $lct1->id)->with('info', 'Ubicación UNO actualizada con éxito');
                }
            } else {
                return response()->json(['error' => 'Ubicación 1 no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lct1  $lct1
     * @return \Illuminate\Http\Response
     */
    public function destroy($lct1)
    {
        try {
            $lct1Located = Lct1::find($lct1);
            if($lct1Located) {
                $animals = Animal::where('lct1_id', $lct1)->get();
                $lct2s = Lct2::where('lct1_id', $lct1)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                }
                elseif ($lct2s->isNotEmpty()) {
                    $lct2Name = array();
                    foreach ($lct2s as $lct2) {
                        $lct2Name[] = $lct2->name;
                    }
                    return response()->json(['conflicto' => $lct2Name], 409);
                } else {
                    $lct1Located->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Ubicación UNO: ' . $lct1Located->name . ' eliminada con éxito'], 204);
                    } else {
                        return redirect()->route('lct1s.index')->with('info', 'Ubicación UNO eliminada con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Ubicación UNO no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display the locations 1 belonging to the specific area.
     *
     * @param  \App\Lct1  $area_id
     * @return \Illuminate\Http\Response
     */
    public function showByArea($area_id)
    {
        $lct1s = Lct1::where('area_id', $area_id)->get()->sortBy('name')->values();
        if($lct1s->isNotEmpty()) {
            return response()->json($lct1s, 200);
        } else {
            return response()->json(['error' => 'Ubicación 1 sin Area'], 406);
        }
    }
}
