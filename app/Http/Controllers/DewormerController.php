<?php

namespace App\Http\Controllers;

use App\Dewormer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class DewormerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dewormers.index')->only('index');
        $this->middleware('permission:dewormers.create')->only(['create', 'store']);
        $this->middleware('permission:dewormers.show')->only('show');
        $this->middleware('permission:dewormers.edit')->only(['edit', 'update']);
        $this->middleware('permission:dewormers.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dewormer = Dewormer::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($dewormer, 200);
        } else {
            $varVacDewVits = $dewormer;
            $labelVacDewVit = 'Desparasitante';
            $model = 'dewormers';
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
        $labelVacDewVit = 'Desparasitante';
        $model = 'dewormers';
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
              'name'            => 'required',
              'characteristic'  => 'required',
              'expiration_date' => 'required|date',
              'lot'             => 'required',
            ]);
            $dewormer = Dewormer::create([
              'name'            => $data['name'],
              'characteristic'  => $data['characteristic'],
              'expiration_date' => $data['expiration_date'],
              'lot'             => $data['lot'],
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($dewormer, 201);
            } else {
                return redirect()->route('dewormers.edit', $dewormer->id)->with('info', 'Desparasitante guardado con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function show($dewormer)
    {
        $dewormer = Dewormer::find($dewormer);
        if($dewormer) {
            return response()->json($dewormer, 200);
        } else {
            return response()->json(['error' => 'Desparasitante no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function edit(Dewormer $dewormer)
    {
        $varVacDewVit = Dewormer::find($dewormer->id);
        $labelVacDewVit = 'Desparasitante';
        $model = 'dewormers';
        return view('configuration.VacDewVit.editVacDewVit', compact('varVacDewVit', 'labelVacDewVit', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $dewormer)
    {
        try {
            $data = $request->validate([
              'name'            => 'required',
              'characteristic'  => 'required',
              'expiration_date' => 'required|date',
              'lot'             => 'required',
            ]);
            $dewormer = Dewormer::find($dewormer);
            if($dewormer) {
                $dewormer->name             = $data['name'];
                $dewormer->characteristic   = $data['characteristic'];
                $dewormer->expiration_date  = $data['expiration_date'];
                $dewormer->lot              = $data['lot'];
                $dewormer->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($dewormer, 201);
                } else {
                    return redirect()->route('dewormers.edit', $dewormer->id)->with('info', 'Desparasitante actualizado con éxito');
                }
            } else {
                return response()->json(['error' => 'Desparasitante no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dewormer  $dewormer
     * @return \Illuminate\Http\Response
     */
    public function destroy($dewormer)
    {
        try {
            $dewormer = Dewormer::find($dewormer);
            if($dewormer) {
                $animals = $dewormer->animalDewormers;
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $dewormer->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Desparasitante: ' . $dewormer->name . ' eliminada con éxito'], 204);
                    } else {
                        return redirect()->route('dewormers.index')->with('info', 'Desparasitante eliminado con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Desparasitante no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Dewormer.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalDewormers()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $dewormers = \App\Dewormer::all();
        $totalDewormers = array();
        if (request()->header('Content-Type') == 'application/json') {
            foreach ($dewormers as $dewormer)
            {
                $name   = $dewormer->name;
                $totalDewormers[] = $name;
                $animals = $dewormer->animalDewormers;
                foreach ($animals as $animal)
                {
                    $totalDewormers[] = "\t\t" . $animal->animal_rfid . "\t\t\t\t\t\t\t\t" . $animal->pivot->application_date->format('d/m/Y');
                }
            }
            return response()->json(['totals' => $totalDewormers], 200);
        } else {
            foreach ($dewormers as $dewormer)
            {
                $arrNivelCero = array('nivel'            => 1,
                                      'vaccOrfid'        => $dewormer->name,
                                      'application_date' => null);
                $totalDewormers[] = $arrNivelCero;
                $animals = $dewormer->animalDewormers;
                foreach ($animals as $animal)
                {
                    $arrNivelUno = array('nivel'            => 4,
                                         'vaccOrfid'        => $animal->animal_rfid,
                                         'application_date' => $animal->pivot->application_date->format('d/m/Y'));
                    $totalDewormers[] = $arrNivelUno;
                }
            }
            $totalGenerals = $totalDewormers;
            $labelVacDewVit = 'Desparasitantes';
            $nameRoute = 'dewormers.DewormerPDF';
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
        $dewormers = \App\Dewormer::all();
        $totalDewormers = array();
        foreach ($dewormers as $dewormer)
        {
            $arrNivelCero = array('nivel'            => 1,
                                  'vaccOrfid'        => $dewormer->name,
                                  'application_date' => null);
            $totalDewormers[] = $arrNivelCero;
            $animals = $dewormer->animalDewormers;
            foreach ($animals as $animal)
            {
                $arrNivelUno = array('nivel'            => 4,
                                     'vaccOrfid'        => $animal->animal_rfid,
                                     'application_date' => $animal->pivot->application_date->format('d/m/Y'));
                $totalDewormers[] = $arrNivelUno;
            }
        }
        $totalGenerals = $totalDewormers;
        $labelVacDewVit = 'Desparasitantes';

        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf ->loadView('report.pdf.VacDewVitExamPDF', compact('totalGenerals', 'labelVacDewVit'));
        return $pdf->download('desparasitantes.pdf');
    }
}
