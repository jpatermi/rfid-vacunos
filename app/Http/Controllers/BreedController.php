<?php

namespace App\Http\Controllers;

use App\Breed;
use App\Animal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class BreedController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:breeds.index')->only('index');
        $this->middleware('permission:breeds.create')->only(['create', 'store']);
        $this->middleware('permission:breeds.show')->only('show');
        $this->middleware('permission:breeds.edit')->only(['edit', 'update']);
        $this->middleware('permission:breeds.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breeds = Breed::all()->sortBy('name')->values();
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($breeds, 200);
        } else {
            $varGenerals = $breeds;
            $labelGeneral = 'Raza';
            $model = 'breeds';
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
        $labelGeneral = 'Raza';
        $model = 'breeds';
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

            // if (request()->header('Content-Type') == 'application/json') {
            //     $data = $request->json()->all();
            // } else {
            //     $data = $request->all();
            // }
            $breed = Breed::create([
              'name' => $data['name']
            ]);
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($breed, 201);
            } else {
                return redirect()->route('breeds.edit', $breed->id)->with('info', 'Raza guardada con éxito');
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function show(Breed $breed)
    {
        $breed = Breed::find($breed->id);
        if (request()->header('Content-Type') == 'application/json')
        {
            if($breed)
            {
                return response()->json($breed, 200);
            }
            else
            {
                return response()->json(['error' => 'Raza no existente'], 406);
            }
        }
        else
        {
            return view('configuration.general.showGeneral', compact('breed'));
        }
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function edit(Breed $breed)
    {
        //$breed = Breed::find($breed);
        $varGeneral = $breed;
        $labelGeneral = 'Raza';
        $model = 'breeds';
        return view('configuration.general.editGeneral', compact('varGeneral', 'labelGeneral', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Breed $breed)
    {
        try {
            $data = $request->validate([
                'name' => 'required'
            ]);

            $breed = Breed::find($breed->id);
            if($breed) {
                $breed->name = $data['name'];
                $breed->save();
                if (request()->header('Content-Type') == 'application/json')
                {
                    return response()->json($breed, 201);
                }
                else
                {
                    return redirect()->route('breeds.edit', $breed->id)->with('info', 'Raza actualizada con éxito');
                }

            } else {
                return response()->json(['error' => 'Raza no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Breed  $breed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Breed $breed)
    {
        try {
            $breedLocated = Breed::find($breed->id);
            if($breedLocated) {
                $animals = Animal::where('breed_id', $breed->id)->get();
                if ($animals->isNotEmpty()) {
                    $animalRFID = array();
                    foreach ($animals as $animal) {
                        $animalRFID[] = $animal->animal_rfid;
                    }
                    return response()->json(['conflicto' => $animalRFID], 409);
                } else {
                    $breedLocated->delete();
                    if (request()->header('Content-Type') == 'application/json')
                    {
                        return response()->json(['exitoso' => 'Raza: ' . $breedLocated->name . ' eliminada con éxito'], 204);
                    }
                    else
                    {
                        return redirect()->route('breeds.index')->with('info', 'Raza eliminada con éxito');
                    }
                }
            } else {
                return response()->json(['error' => 'Raza no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }
    /**
     * Display a Total of the resource by Breeds.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalsBreeds()
    {
        /*** Con este me traigo el total de Animales por Razas ****/
        $breeds = \App\Breed::withCount('animals')->get()->where("animals_count", ">", 0);
        $totalBreeds = array();
        foreach ($breeds as $breed) {
            $name   = $breed->name;
            for ($x = 1; $x <= (22 - strlen($breed->name)); $x++)
            {
                $name = $name . "\t";
            }
            $totalBreeds[] = $name . $breed->animals_count;
        }
        return response()->json(['totals' => $totalBreeds], 200);
    }
    /**
     * Export report to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $breeds = \App\Breed::withCount('animals')->get()->where("animals_count", ">", 0);

        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf ->loadView('report.pdf.InvBreedsPDF', compact('totalAreas', 'total', 'breeds'));
        return $pdf->download('inv-razas.pdf');
    }
}
