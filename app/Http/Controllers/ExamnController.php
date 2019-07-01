<?php

namespace App\Http\Controllers;

use App\Examn;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class ExamnController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:examns.index')->only('index');
        $this->middleware('permission:examns.create')->only(['create', 'store']);
        $this->middleware('permission:examns.show')->only('show');
        $this->middleware('permission:examns.edit')->only(['edit', 'update']);
        $this->middleware('permission:examns.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examn = Examn::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($examn, 200);
        } else {
            $varVacDewVits = $examn;
            $labelVacDewVit = 'Examen';
            $model = 'examns';
            return view('configuration.VacDewVit.indexVacDewVit', compact('varVacDewVits', 'labelVacDewVit', 'model'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $labelVacDewVit = 'Examen';
        $model = 'examns';
        return view('configuration.VacDewVit.createVacDewVit', compact('labelVacDewVit', 'model'));
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
              'name' => 'required',
              'characteristic' => 'required',
            ]);
            $examn = Examn::create([
              'name' => $data['name'],
              'characteristic' => $data['characteristic'],
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($examn, 201);
            } else {
                return redirect()->route('examns.edit', $examn->id)->with('info', 'Examen guardado con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function show($examn)
    {
        $examn = Examn::find($examn);
        if($examn) {
            return response()->json($examn, 200);
        } else {
            return response()->json(['error' => 'Examna no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function edit(Examn $examn)
    {
        $varVacDewVit = Examn::find($examn->id);
        $labelVacDewVit = 'Examen';
        $model = 'examns';
        return view('configuration.VacDewVit.editVacDewVit', compact('varVacDewVit', 'labelVacDewVit', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $examn)
    {
        try {
            $data = $request->validate([
              'name' => 'required',
              'characteristic' => 'required',
            ]);
            $examn = Examn::find($examn);
            if($examn) {
                $examn->name = $data['name'];
                $examn->characteristic = $data['characteristic'];
                $examn->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($examn, 201);
                } else {
                    return redirect()->route('examns.edit', $examn->id)->with('info', 'Examen ctualizado con éxito');
                }
            } else {
                return response()->json(['error' => 'Examen no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Examn  $examn
     * @return \Illuminate\Http\Response
     */
    public function destroy($examn)
    {
        try {
            $examn = Examn::find($examn);
            if($examn) {
                $animals = $examn->animalExamns;
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $examn->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Examen: ' . $examn->name . ' eliminado con éxito'], 204);
                    } else {
                        return redirect()->route('examns.index')->with('info', 'Examen eliminado con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Examen no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Examn.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalExamns()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $examns = \App\Examn::all();
        $totalExamns = array();
        if (request()->header('Content-Type') == 'application/json') {
            foreach ($examns as $examn)
            {
                $name   = $examn->name;
                $totalExamns[] = $name;
                $animals = $examn->AnimalExamns;
                foreach ($animals as $animal)
                {
                    $totalExamns[] = "\t\t" . $animal->animal_rfid . "\t\t\t\t\t\t\t\t" . $animal->pivot->application_date->format('d/m/Y');
                }
            }
            return response()->json(['totals' => $totalExamns], 200);
        } else {
            foreach ($examns as $examn)
            {
                $arrNivelCero = array('nivel'            => 1,
                                      'vaccOrfid'        => $examn->name,
                                      'application_date' => null);
                $totalExamns[] = $arrNivelCero;
                $animals = $examn->AnimalExamns;
                foreach ($animals as $animal)
                {
                    $arrNivelUno = array('nivel'             => 4,
                                         'vaccOrfid'         => $animal->animal_rfid,
                                         'application_date'  => $animal->pivot->application_date->format('d/m/Y'));
                    $totalExamns[] = $arrNivelUno;
                }
            }
            $totalGenerals = $totalExamns;
            $labelVacDewVit = 'Exámenes';
            $nameRoute = 'examns.ExamnPDF';
            return view('report.general.totalAnimalsGeneral', compact('totalGenerals', 'labelVacDewVit', 'nameRoute'));
        }
    }
    /**
     * Export report to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $examns = \App\Examn::all();
        $totalExamns = array();
        foreach ($examns as $examn)
        {
            $arrNivelCero = array('nivel'            => 1,
                                  'vaccOrfid'        => $examn->name,
                                  'application_date' => null);
            $totalExamns[] = $arrNivelCero;
            $animals = $examn->AnimalExamns;
            foreach ($animals as $animal)
            {
                $arrNivelUno = array('nivel'             => 4,
                                     'vaccOrfid'         => $animal->animal_rfid,
                                     'application_date'  => $animal->pivot->application_date->format('d/m/Y'));
                $totalExamns[] = $arrNivelUno;
            }
        }
        $totalGenerals = $totalExamns;
        $labelVacDewVit = 'Exámenes';

        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf ->loadView('report.pdf.VacDewVitExamPDF', compact('totalGenerals', 'labelVacDewVit'));
        return $pdf->download('examenes.pdf');
    }
}
