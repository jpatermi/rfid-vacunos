<?php

namespace App\Http\Controllers;

use App\AgeGroup;
use Illuminate\Http\Request;
use App\Animal;
use Barryvdh\DomPDF\Facade as PDF;

class AgeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ageGroups = AgeGroup::all()->sortBy('id')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($ageGroups, 200);
        } else {
            $varGenerals = $ageGroups;
            $labelGeneral = 'Grupo Etario';
            $model = 'agegroups';
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
        $labelGeneral = 'Grupo Etario';
        $model = 'agegroups';
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
            $ageGroup = AgeGroup::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($ageGroup, 201);
            } else {
                return redirect()->route('agegroups.edit', $ageGroup->id)->with('info', 'Grupo Etario guardado con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function show($ageGroup)
    {
        $ageGroup = AgeGroup::find($ageGroup);
        if($ageGroup) {
            return response()->json($ageGroup, 200);
        } else {
            return response()->json(['error' => 'Grupo Etario no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function edit($ageGroup)
    {
        $varGeneral = AgeGroup::find($ageGroup);
        $labelGeneral = 'Grupo Etario';
        $model = 'agegroups';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ageGroup)
    {
        try {
            $data = $request->validate([
                'name' => 'required'
            ]);
            $ageGroup = AgeGroup::find($ageGroup);
            if($ageGroup) {
                $ageGroup->name = $data['name'];
                $ageGroup->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($ageGroup, 201);
                } else {
                    return redirect()->route('agegroups.edit', $ageGroup->id)->with('info', 'Grupo Etario actualizado con éxito');
                }
            } else {
                return response()->json(['error' => 'Grupo Etario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgeGroup  $ageGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($ageGroup)
    {
        try {
            $ageGroupLocated = AgeGroup::find($ageGroup);
            if($ageGroupLocated) {
                $animals = Animal::where('age_group_id', $ageGroup)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $ageGroupLocated->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Grupo Etario: ' . $ageGroupLocated->name . ' eliminado con éxito'], 204);
                    } else {
                        return redirect()->route('agegroups.index')->with('info', 'Grupo Etario eliminado con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Grupo Etario no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Age Group.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalsAgeGroups()
    {
        /*** Con este me traigo el total de Animales por Grupo Etario ****/
        $ageGroups = \App\AgeGroup::withCount('animals')->get()->where("animals_count", ">", 0);
        if (request()->header('Content-Type') == 'application/json') {
            $totalAgeGroup = array();
            foreach ($ageGroups as $ageGroup) {
                $name   = $ageGroup->name;
                for ($x = 1; $x <= (25 - strlen($ageGroup->name)); $x++)
                {
                    $name = $name . "\t";
                }
                $totalAgeGroup[] = $name . $ageGroup->animals_count;
            }
            return response()->json(['totals' => $totalAgeGroup], 200);
        } else {
            return view('report.ageGroup.totalAnimalsAgeGroups', compact('ageGroups'));
        }
    }
    /**
     * Export report to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $ageGroups = \App\AgeGroup::withCount('animals')->get()->where("animals_count", ">", 0);
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf ->loadView('report.pdf.AgeGroupsPDF', compact('ageGroups'));
        return $pdf->download('grupo-etario.pdf');
    }
    /**
     * Get the animals of the resource Age Group.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAgeGroupAnimals(AgeGroup $ageGroup)
    {
        $animals = $ageGroup->animals->sortBy('animal_rfid')->values();
        return response()->json($animals, 200);
    }
}
