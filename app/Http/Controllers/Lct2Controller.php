<?php

namespace App\Http\Controllers;

use App\Lct2;
use Illuminate\Http\Request;
use App\Area;
use App\Lct1;
use App\Animal;

class Lct2Controller extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:lct2s.index')->only('index');
        $this->middleware('permission:lct2s.create')->only(['create', 'store']);
        $this->middleware('permission:lct2s.show')->only('show');
        $this->middleware('permission:lct2s.edit')->only(['edit', 'update']);
        $this->middleware('permission:lct2s.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lct2s = Lct2::orderBy('lct1_id')->orderBy('name')->get();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($lct2s, 200);
        } else {
            $areas = Area::orderBy('name')->get();
            foreach ($areas as $area) {
                $area->lct1s->sortBy('name');
                foreach ($area->lct1s as $lct1) {
                    $lct1->lct2s->sortBy('name');
                }
            }
            foreach ($areas as $area) {
                foreach ($area->lct1s as $lct1) {
                    foreach ($lct1->lct2s as $lct2) {
                        $arrAreaLct1Lct2s[] = array('id'        => $lct2->id,
                                                    'name'      => $lct2->name,
                                                    'nameSup'   => $lct1->name,
                                                    'nameSupSup'=> $area->name);
                    }
                }
            }
            $model = 'lct2s';
            $labelAreaLct1Lct2 = 'Ubicación DOS';
            $labelNameSup = 'Ubicación UNO';
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
        $lct1s = Lct1::orderBy('area_id')->orderBy('name')->get();
        foreach ($lct1s as $lct1) {
            $arrLct1s[] = array('id'     => $lct1->id,
                                'name'   => $lct1->name . ' --- ' . $lct1->area->name);
        }
        $varComboSups = $arrLct1s;
        $model = 'lct2s';
        $labelAreaLct1Lct2 = 'Ubicación DOS';
        $labelNameSup = 'Ubcicación UNO';
        $fieldNameSup = 'lct1_id';
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
                'lct1_id'   => 'required'
            ]);
            $lct2 = Lct2::create([
              'name' => $data['name'],
              'lct1_id' => $data['lct1_id']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($lct2, 201);
            } else {
                return redirect()->route('lct2s.edit', $lct2->id)->with('info', 'Ubicación DOS guardada con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function show($lct2)
    {
        $lct2 = Lct2::find($lct2);
        if($lct2) {
            return response()->json($lct2, 200);
        } else {
            return response()->json(['error' => 'Ubicación 2 no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function edit($lct2)
    {
        $lct1s = Lct1::orderBy('area_id')->orderBy('name')->get();
        foreach ($lct1s as $lct1) {
            $arrLct1s[] = array('id'     => $lct1->id,
                                'name'   => $lct1->name . ' --- ' . $lct1->area->name);
        }

        $lct2 = Lct2::find($lct2);
        $arrAreaLct1Lct2 = array('id'       => $lct2->id,
                                 'name'     => $lct2->name,
                                 'idSup'    => $lct2->lct1->id,
                                 'nameSup'  => $lct2->lct1->name);

        $varComboSups = $arrLct1s;
        $model = 'lct2s';
        $labelAreaLct1Lct2 = 'Ubicación DOS';
        $labelNameSup = 'Ubcicación UNO';
        $fieldNameSup = 'lct1_id';
        return view('configuration.AreasLct1sLct2s.editAreaLct1Lct2', compact('varComboSups', 'model', 'labelAreaLct1Lct2', 'labelNameSup', 'fieldNameSup', 'arrAreaLct1Lct2'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lct2)
    {
        try {
            $data = $request->validate([
                'name'      => 'required',
                'lct1_id'   => 'required'
            ]);
            $lct2 = Lct2::find($lct2);
            if($lct2) {
                $lct2->name = $data['name'];
                $lct2->lct1_id = $data['lct1_id'];
                $lct2->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($lct2, 201);
                } else {
                    return redirect()->route('lct2s.edit', $lct2->id)->with('info', 'Ubicación DOS actualizada con éxito');
                }
            } else {
                return response()->json(['error' => 'Ubicación 2 no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lct2  $lct2
     * @return \Illuminate\Http\Response
     */
    public function destroy($lct2)
    {
        try {
            $lct2Located = Lct2::find($lct2);
            if($lct2Located) {
                $animals = Animal::where('lct2_id', $lct2)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $lct2Located->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Ubicación UNO: ' . $lct2Located->name . ' eliminada con éxito'], 204);
                    } else {
                        return redirect()->route('lct2s.index')->with('info', 'Ubicación DOS eliminada con éxito');
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
     * Display the locations 2 belonging to the specific locations 1.
     *
     * @param  \App\Lct2  $lct1_id
     * @return \Illuminate\Http\Response
     */
    public function showByLct1($lct1_id)
    {
        $lct2s = Lct2::where('lct1_id', $lct1_id)->get()->sortBy('name')->values();
        if($lct2s->isNotEmpty()) {
            return response()->json($lct2s, 200);
        } else {
            return response()->json(['error' => 'Ubicación 2 sin Ubicación 1'], 406);
        }
    }
}
