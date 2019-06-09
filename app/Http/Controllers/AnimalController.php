<?php

namespace App\Http\Controllers;

use App\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AnimalLocation;
use App\HistoricalWeightHeight;
use App\PhysicalCharacteristic;
use App\Breed;
use App\AgeGroup;
use App\Area;
use App\Lct1;
use App\Lct2;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $animals = Animal::all()->sortBy('animal_rfid')->values();
        foreach ($animals as $animal) {
          $animal->physicalCharacteristics;
          $animal->breed;
        }
        if (request()->header('Content-Type') == 'application/json') {
            return response()->json($animals, 200);
        } else {
            return view('/animals/indexAnimal', compact('animals'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breeds = Breed::all();
        $ageGroups = AgeGroup::all();
        $areas = Area::all();
        return view('animals.createAnimal', compact('breeds', 'ageGroups', 'areas'));
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
            global $data, $animal;
            $data = $request->validate([
                'animal_rfid'   => 'required|unique:animals|max:10|min:10', //TODO Revisar el unique (son 2 campos)
                'gender'        => 'required|max:1',
                'birthdate'     => 'required|date|max:10',
                'breed_id'      => 'required',
                'mother_rfid'   => 'required|max:10|min:10',
                'father_rfid'   => 'required|max:10|min:10',
                'last_weight'   => 'required',
                'last_height'   => 'required',
                'age_group_id'  => 'required',
                'farm_id'       => 'required',
                'area_id'       => 'required',
                'lct1_id'       => 'required',
                'lct2_id'       => 'required',
                'user_id'       => 'required',
            ]);

            //$data = $request->json()->all();
            $animal = Animal::where('animal_rfid', $data['animal_rfid'])->get();
            if ($animal->isEmpty())
            {
                DB::transaction(function ()
                {
                    global $data, $animal;
                    $animal = Animal::create([
                          'animal_rfid' => $data['animal_rfid'],
                          'gender' => $data['gender'],
                          'birthdate' => $data['birthdate'],
                          'breed_id' => $data['breed_id'],
                          'mother_rfid' => $data['mother_rfid'],
                          'father_rfid' => $data['father_rfid'],
                          'last_weight' => $data['last_weight'],
                          'last_height' => $data['last_height'],
                          'age_group_id' => $data['age_group_id'],
                          'farm_id' => $data['farm_id'],
                          'area_id' => $data['area_id'],
                          'lct1_id' => $data['lct1_id'],
                          'lct2_id' => $data['lct2_id'],
                          'user_id' => $data['user_id'],
                          //'status_id' => $data['status_id'],
                        ]);
                        AnimalLocation::create([
                          'animal_id' => $animal->id,
                          'farm_id' => $data['farm_id'],
                          'area_id' => $data['area_id'],
                          'lct1_id' => $data['lct1_id'],
                          'lct2_id' => $data['lct2_id'],
                        ]);
                        HistoricalWeightHeight::create([
                          'animal_id' => $animal->id,
                          'weight' => $animal->last_weight,
                          'height' => $animal->last_height,
                          'measurement_date' => date('Y-m-d')
                        ]);
                        // $dataPhysicalCharacteristics = $data['physical_characteristics'];
                        // foreach ($dataPhysicalCharacteristics as $dataPhysicalCharacteristic) {
                        //     PhysicalCharacteristic::create([
                        //       'animal_id' => $animal->id,
                        //       'characteristic' => $dataPhysicalCharacteristic['characteristic'],
                        //     ]);
                        // }
                        // $animal->physicalCharacteristics;
                });
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($animal, 201);
                } else {
                    return redirect()->route('animals.index');
                }
            }
            else
            {
                return response()->json(['error' => 'Animal ' . $data['animal_rfid'] . ' ya existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show($animal_rfid)
    {
        $animal = Animal::where('animal_rfid', $animal_rfid)->get()->first();
        if ($animal) {
            $animal->physicalCharacteristics;
            if (request()->header('Content-Type') == 'application/json') {
                return response()->json($animal, 200);
            } else {
                $animal->breed;
                $animal->area;
                $animal->lct1;
                $animal->lct2;
                $animal->agegroup;
                $animal->physicalCharacteristics;
                $i=0;
                return view('animals.showAnimal', compact('animal', 'i'));
            }
        } else {
            return response()->json(['error' => 'Animal ' . $animal_rfid . ' no existente'], 406);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        $breeds     = Breed::all();
        $ageGroups  = AgeGroup::all();
        $areas      = Area::all();
        $lct1s      = Lct1::where('area_id', $animal->area_id)->get()->sortBy('name')->values();
        $lct2s      = Lct2::where('lct1_id', $animal->lct1_id)->get()->sortBy('name')->values();
        $physicalCharacteristics = $animal->physicalCharacteristics()
                                          ->orderBy('id')
                                          ->get();
        $i=0;
        return view('animals.editAnimal', compact('animal', 'ageGroups', 'areas', 'breeds', 'lct1s', 'lct2s', 'physicalCharacteristics', 'i'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        try {
            global $data, $animalLocated;
            $data = $request->validate([
                'animal_rfid'   => 'required|max:10|min:10', //TODO Revisar el unique (son 2 campos)
                'gender'        => 'required|max:1',
                'birthdate'     => 'required|date|max:10',
                'breed_id'      => 'required',
                'mother_rfid'   => 'required|max:10|min:10',
                'father_rfid'   => 'required|max:10|min:10',
                'last_weight'   => 'required',
                'last_height'   => 'required',
                'age_group_id'  => 'required',
                'farm_id'       => 'required',
                'area_id'       => 'required',
                'lct1_id'       => 'required',
                'lct2_id'       => 'required',
                'user_id'       => 'required',
                'photo'         => '',
            ]);

//            $data = $request->json()->all();
            $animalLocated = Animal::find($animal->id);
            if($animalLocated)
            {
               DB::transaction(function ()
               {
                    global $data, $animalLocated, $request;
                    $animalLocated->animal_rfid = $data['animal_rfid'];
                    $animalLocated->gender = $data['gender'];
                    $animalLocated->birthdate = $data['birthdate'];
                    $animalLocated->breed_id = $data['breed_id'];
                    $animalLocated->mother_rfid = $data['mother_rfid'];
                    $animalLocated->father_rfid = $data['father_rfid'];
                    $animalLocated->age_group_id = $data['age_group_id'];
                    $animalLocated->farm_id = $data['farm_id'];
                    $animalLocated->area_id = $data['area_id'];
                    $animalLocated->lct1_id = $data['lct1_id'];
                    $animalLocated->lct2_id = $data['lct2_id'];
                    $animalLocated->user_id = $data['user_id'];
                    //$animalLocated->status_id = $data['status_id'];
                    $animalLocated->save();
                    /*** Se hace así para que no mande en la colección las ubicaciones por las que ha pasado el Animal ***/
                    $animalResp = Animal::find($animalLocated->id);
                    /*** Se verifica si se actualizó alguna ubicación ***/
                    $animalLocation = $animalResp->animalLocations->sortBy('id')->last();
                    if (($animalLocated->area_id != $animalLocation->area_id) or
                        ($animalLocated->lct1_id != $animalLocation->lct1_id) or
                        ($animalLocated->lct2_id != $animalLocation->lct2_id))
                    {
                        AnimalLocation::create([
                          'animal_id' => $animalLocated->id,
                          'farm_id' => $data['farm_id'],
                          'area_id' => $data['area_id'],
                          'lct1_id' => $data['lct1_id'],
                          'lct2_id' => $data['lct2_id'],
                        ]);
                    }
                    /*** Se verifica si viene la foto ***/
                    if($request->hasFile('photo'))
                    {
                        $file = $request->file('photo');
                        $file->storeAs('public/photo/', $animalLocated->animal_rfid . '.jpg');
                        // Se actualiza en la BD el indicador
                        $animalLocated->photo = true;
                        $animalLocated->save();
                    }
                    else
                    {
                        if(!Storage::disk('local')->exists('/public/photo/' . $animalLocated->animal_rfid . '.jpg')) {
                            $animalLocated->photo = false;
                            $animalLocated->save();
                        }
                    }
                });
                if (request()->header('Content-Type') == 'application/json') {
                    return response()->json($animalLocated, 201);
                } else {
                    return redirect()->route('animals.index');
                }
            }
            else
            {
                return response()->json(['error' => 'Animal no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        //
    }

    /**
     * Upload a newly photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function uploadPhoto(Request $request, $animal_rfid)
    {
        try {
            //dd($request);
            if($request->hasFile('photo'))
            {
                $file = $request->file('photo');
                $file->storeAs('public/photo/', $animal_rfid . '.jpg');
                // Se actualiza en la BD el indicador
                $animal = Animal::where('animal_rfid', $animal_rfid)->get()->first();
                if ($animal)
                {
                    $animal->photo = true;
                    $animal->save();
                    return response()->json(['exitoso' => 'Foto guardada en la Web con éxito'], 204);
                }
                else
                {
                    return response()->json(['error' => 'Animal ' . $animal_rfid . ' no existente'], 406);
                }
            }
            else
            {
                $file = file_get_contents('php://input');
                if(file_put_contents(storage_path() . '/public/photo/' . $animal_rfid . '.jpg', $file))
                {
                    $animal = Animal::where('animal_rfid', $animal_rfid)->get()->first();
                    if ($animal)
                    {
                        $animal->photo = true;
                        $animal->save();
                        return response()->json(['exitoso' => 'Foto guardada en la Web con éxito'], 204);
                    }
                    else
                    {
                        return response()->json(['error' => 'Animal ' . $animal_rfid . ' no existente'], 406);
                    }
                }
                else
                {
                    return response()->json(['error' => 'No se almacenó la foto en el servidor Web'], 500);
                }
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => $e->message()], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Animal  $animal_rfid
     * @return \Illuminate\Http\Response
     */
    public function showPhoto($animal_rfid)
    {
        if (!file_exists(public_path() . '/photo/' . $animal_rfid . '.jpg')) {
            $animal_rfid = 'no_existe';
        }
        $configuration = \App\Configuration::first();
        $url_photo = $configuration->url_photo;
        //dd($url_photo . "/". $animal_rfid);
        return view("photo.showphoto")->with('animal_rfid', $animal_rfid)->with('url_photo', $url_photo);
    }
    /**
     * Display a Total of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimals()
    {
        $totalAnimals = Animal::count();
        return response()->json(['totalAnimals' => $totalAnimals], 200);
    }
    /**
     * Display a Total of the resource by Areas.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalAnimalsAreas()
    {
        /*** Con este me traigo el total de Animales por áreas ****/
        $areas = \App\Area::withCount('animals')->get()->where("animals_count", ">", 0);
        $totalAreas = array();
        global $areaId, $lct1Id;
        foreach ($areas as $area)
        {
            $name   = $area->name;
            $areaId = $area->id;
            for ($x = 1; $x <= (25 - strlen($area->name)); $x++)
            {
                $name = $name . "\t";
            }
            $totalAreas[] = $name . $area->animals_count;
            /*** Al recorrer la colección de las áreas, me traigo el total de Animales por Módulo de cada área ****/
            $lct1s = \App\Lct1::withCount(['animals' => function ($query) {global $areaId; $query->where('area_id', $areaId);}])->get()->where("animals_count", ">", 0);
            foreach ($lct1s as $lct1)
            {
                $name = $lct1->name;
                $lct1Id = $lct1->id;
                for ($x = 1; $x <= (25 - strlen($lct1->name)); $x++)
                {
                    $name = $name . "\t";
                }
                $totalAreas[] = "\t" . $name . $lct1->animals_count;
                /*** Al recorrer la colección de las lct1s, me traigo el total de Animales por Corral de cada Módulo ****/
                $lct2s = \App\Lct2::withCount(['animals' => function ($query) {global $lct1Id; $query->where('lct1_id', $lct1Id);}])->get()->where("animals_count", ">", 0);
                foreach ($lct2s as $lct2)
                {
                    $name = $lct2->name;
                    for ($x = 1; $x <= (25 - strlen($lct2->name)); $x++)
                    {
                        $name = $name . "\t";
                    }
                    $totalAreas[] = "\t\t" . $name . $lct2->animals_count;
                }
            }
        }
        return response()->json(['totals' => $totalAreas], 200);
    }
}
