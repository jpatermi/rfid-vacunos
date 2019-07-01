<?php

namespace App\Http\Controllers;

use App\Vaccination;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class VaccinationController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:vaccinations.index')->only('index');
        $this->middleware('permission:vaccinations.create')->only(['create', 'store']);
        $this->middleware('permission:vaccinations.show')->only('show');
        $this->middleware('permission:vaccinations.edit')->only(['edit', 'update']);
        $this->middleware('permission:vaccinations.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vaccination = Vaccination::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($vaccination, 200);
        } else {
            $varVacDewVits = $vaccination;
            $labelVacDewVit = 'Vacuna';
            $model = 'vaccinations';
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
        $labelVacDewVit = 'Vacuna';
        $model = 'vaccinations';
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
            $vaccination = Vaccination::create([
              'name' => $data['name'],
              'characteristic' => $data['characteristic'],
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($vaccination, 201);
            } else {
                return redirect()->route('vaccinations.edit', $vaccination->id)->with('info', 'Vacuna guardada con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function show($vaccination)
    {
        $vaccination = Vaccination::find($vaccination);
        if($vaccination) {
            return response()->json($vaccination, 200);
        } else {
            return response()->json(['error' => 'Vacuna no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function edit(Vaccination $vaccination)
    {
        $varVacDewVit = Vaccination::find($vaccination->id);
        $labelVacDewVit = 'Vacuna';
        $model = 'vaccinations';
        return view('configuration.VacDewVit.editVacDewVit', compact('varVacDewVit', 'labelVacDewVit', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vaccination)
    {
        try {
            $data = $request->validate([
              'name' => 'required',
              'characteristic' => 'required',
            ]);
            $vaccination = Vaccination::find($vaccination);
            if($vaccination) {
                $vaccination->name = $data['name'];
                $vaccination->characteristic = $data['characteristic'];
                $vaccination->save();
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($vaccination, 201);
                } else {
                    return redirect()->route('vaccinations.edit', $vaccination->id)->with('info', 'Vacuna actualizada con éxito');
                }
            } else {
                return response()->json(['error' => 'Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function destroy($vaccination)
    {
        try {
            $vaccination = Vaccination::find($vaccination);
            if($vaccination) {
                $animalVaccinations = $vaccination->animalVaccinations;
                if ($animalVaccinations->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animalVaccinations as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $vaccination->delete();
                    if (request()->header('Content-Type') == 'application/json') {
                        return response()->json(['exitoso' => 'Vacuna: ' . $vaccination->name . ' eliminada con éxito'], 204);
                    } else {
                        return redirect()->route('vaccinations.index')->with('info', 'Vacuna eliminada con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Vacuna no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Vaccinations.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalVaccinations()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $vaccinations = \App\Vaccination::all();
        $totalVaccinations = array();
        if (request()->header('Content-Type') == 'application/json') {
            foreach ($vaccinations as $vaccination)
            {
                $name   = $vaccination->name;
                $totalVaccinations[] = $name;
                $animals = $vaccination->AnimalVaccinations;
                foreach ($animals as $animal)
                {
                    $totalVaccinations[] = "\t\t" . $animal->animal_rfid . "\t\t\t\t\t\t\t\t" . $animal->pivot->application_date->format('d/m/Y');
                }
            }
            return response()->json(['totals' => $totalVaccinations], 200);
        } else {
            foreach ($vaccinations as $vaccination)
            {
                $arrNivelCero = array('nivel'            => 1,
                                      'vaccOrfid'        => $vaccination->name,
                                      'application_date' => null);
                $totalVaccinations[] = $arrNivelCero;
                $animals = $vaccination->AnimalVaccinations;
                foreach ($animals as $animal)
                {
                    $arrNivelUno = array('nivel'             => 4,
                                         'vaccOrfid'         => $animal->animal_rfid,
                                         'application_date'  => $animal->pivot->application_date->format('d/m/Y'));
                    $totalVaccinations[] = $arrNivelUno;
                }
            }
            $totalGenerals = $totalVaccinations;
            $labelVacDewVit = 'Vacunas';
            $nameRoute = 'vaccinations.VaccinationPDF';
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
        $vaccinations = \App\Vaccination::all();
        $totalVaccinations = array();
        foreach ($vaccinations as $vaccination)
        {
            $arrNivelCero = array('nivel'            => 1,
                                  'vaccOrfid'        => $vaccination->name,
                                  'application_date' => null);
            $totalVaccinations[] = $arrNivelCero;
            $animals = $vaccination->AnimalVaccinations;
            foreach ($animals as $animal)
            {
                $arrNivelUno = array('nivel'             => 4,
                                     'vaccOrfid'         => $animal->animal_rfid,
                                     'application_date'  => $animal->pivot->application_date->format('d/m/Y'));
                $totalVaccinations[] = $arrNivelUno;
            }
        }
        $totalGenerals = $totalVaccinations;
        $labelVacDewVit = 'Vacunas';

        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf ->loadView('report.pdf.VacDewVitExamPDF', compact('totalGenerals', 'labelVacDewVit'));
        return $pdf->download('vacunas.pdf');
    }
}
