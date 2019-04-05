<?php

namespace App\Http\Controllers;

use App\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configurations = Configuration::all();
        return response()->json($configurations, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function show(Configuration $configuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuration $configuration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Configuration  $configuration->read_value
     * @return \Illuminate\Http\Response
     */
    public function updateReadValue(Request $request, $animal_rfid)
    {
        try {
            $data = $request->json()->all();
            $configuration = Configuration::first(); //Busco siempre el único registro que debe existir
            if($configuration) {
                $configuration->read_value = $data['animal_rfid'];
                $configuration->save();
                return response()->json(['read_value' => $configuration->read_value], 201);
            } else {
                return response()->json(['error' => 'Configuración no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Configuration  $configuration->ip_server
     * @return \Illuminate\Http\Response
     */
    public function updateUrlPhoto(Request $request, $configuration)
    {
        try {
            $data = $request->json()->all();
            $configuration = Configuration::first(); //Busco siempre el único registro que debe existir
            if($configuration) {
                $configuration->url_photo = $data['url_photo'];
                $configuration->save();
                return response()->json(['url_photo' => $configuration->url_photo], 201);
            } else {
                return response()->json(['error' => 'Configuración no existente'], 406);
            }
        } catch (ModelNotFoundException $e){ // TODO: Averiguar el modelo para database
            return response()->json(['error' => 'Sin contenido'], 406);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Configuration  $configuration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuration $configuration)
    {
        //
    }
    /**
     * Display ID of the animal readed.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadID()
    {
        $configuration = Configuration::first();
        $read_value = $configuration->read_value;
        $configuration->read_value = '0000000000';
        $configuration->save();
        return response()->json(['read_value' => $read_value], 200);
    }
    /**
     * Get the IP of the production server.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadUrlPhoto()
    {
        $configuration = Configuration::first();
        return response()->json(['url_photo' => $configuration->url_photo], 200);
    }

}
