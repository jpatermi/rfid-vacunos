<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;
use App\Animal;
use App\Lct1;
use App\Farm;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas= Area::orderBy('farm_id')->orderBy('name')->get();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($areas, 200);
        } else {
            foreach ($areas as $area) {
                $arrAreaLct1Lct2s[] = array('id'       => $area->id,
                                            'name'     => $area->name,
                                            'nameSup'  => $area->farm->name);
            }
            $model = 'areas';
            $labelAreaLct1Lct2 = 'Área';
            $labelNameSup = 'Granja';
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
        $varComboSups = Farm::all()->sortBy('name');
        $model = 'areas';
        $labelAreaLct1Lct2 = 'Área';
        $labelNameSup = 'Granja';
        $fieldNameSup = 'farm_id';
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
                'farm_id'   => 'required'
            ]);
            $area = Area::create([
              'name' => $data['name'],
              'farm_id' => $data['farm_id']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($area, 201);
            } else {
                return redirect()->route('areas.index');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show($area)
    {
        $area = Area::find($area);
        if($area) {
            return response()->json($area, 200);
        } else {
            return response()->json(['error' => 'Área no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($area)
    {
        $area = Area::find($area);
        $arrAreaLct1Lct2 = array('id'       => $area->id,
                                 'name'     => $area->name,
                                 'idSup'    => $area->farm->id,
                                 'nameSup'  => $area->farm->name);
        $varComboSups = Farm::all()->sortBy('name');
        $model = 'areas';
        $labelAreaLct1Lct2 = 'Área';
        $labelNameSup = 'Granja';
        $fieldNameSup = 'farm_id';
        return view('configuration.AreasLct1sLct2s.editAreaLct1Lct2', compact('varComboSups', 'model', 'labelAreaLct1Lct2', 'labelNameSup', 'arrAreaLct1Lct2', 'fieldNameSup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $area)
    {
        try {
            $data = $request->validate([
                'name'      => 'required',
                'farm_id'   => 'required'
            ]);
            $area = Area::find($area);
            if($area) {
                $area->name = $data['name'];
                $area->farm_id = $data['farm_id'];
                $area->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($area, 201);
                } else {
                    return redirect()->route('areas.index');
                }
            } else {
                return response()->json(['error' => 'Área no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($area)
    {
        try {
            $areaLocated = Area::find($area);
            if($areaLocated) {
                $animals = Animal::where('area_id', $area)->get();
                $lct1s = Lct1::where('area_id', $area)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                }
                elseif ($lct1s->isNotEmpty()) {
                    $lct1Name = array();
                    foreach ($lct1s as $lct1) {
                        $lct1Name[] = $lct1->name;
                    }
                    return response()->json(['conflicto' => $lct1Name], 409);
                } else {
                    $areaLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Area: ' . $areaLocated->name . ' eliminada con éxito'], 204);
                    } else {
                        return redirect()->route('areas.index');
                    }
                }
            } else {
                return response()->json(['error' => 'Area no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
}
