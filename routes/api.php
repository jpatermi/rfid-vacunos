<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*** Login ***/
Route::post('/users/login', ['uses'=>'UsersController@loginGetToken'])->middleware('isJson');

/*** Photos ***/
Route::post('/photo/{animal_rfid}', ['uses'=>'AnimalController@uploadPhoto'])->middleware('auth');
Route::get('/photo/{animal_rfid}', ['uses'=>'AnimalController@showPhoto']);

/*** Configuration URL photo Animal ***/
Route::get('/configurlphoto', ['uses'=>'ConfigurationController@loadUrlPhoto']);
Route::post('/configurlphoto/{id}', ['uses'=>'ConfigurationController@updateUrlPhoto']);

Route::group(['middleware' => ['auth', 'isJson']], function() use($router) {
	Route::resource('users', 'UsersController');
	Route::resource('areas', 'AreaController');
	Route::resource('farms', 'FarmController');
	Route::resource('lct1s', 'Lct1Controller');
	Route::resource('lct2s', 'Lct2Controller');
	Route::resource('lct3s', 'Lct3Controller');
	Route::resource('breeds', 'BreedController');
	Route::resource('animals', 'AnimalController');
	Route::resource('vaccinations', 'VaccinationController');
	Route::resource('dewormers', 'DewormerController');
	Route::resource('vitamins', 'VitaminController');
	Route::resource('animalvaccination', 'AnimalVaccinationController');
	Route::resource('animaldewormer', 'AnimalDewormerController');
	Route::resource('animalvitamin', 'AnimalVitaminController');
	Route::resource('agegroups', 'AgeGroupController');
	Route::resource('animallocations', 'AnimalLocationController');
	Route::resource('veterinarians', 'VeterinarianController');
	Route::resource('diagnostics', 'DiagnosticController');
	Route::resource('causes', 'CauseController');
	Route::resource('treatments', 'TreatmentController');
	Route::resource('responsibles', 'ResponsibleController');
	Route::resource('diseases', 'DiseaseController');
	Route::get('/animaldiseases/{animal_rfid}', ['uses'=>'DiseaseController@getAnimalDiseases']);
	Route::resource('historicals', 'HistoricalWeightHeightController');
	Route::resource('productions', 'ProductionController');

	/*** Inventory ***/
	Route::get('/totalanimals', ['uses'=>'AnimalController@totalAnimals']);
	Route::get('/totalanimalsareas', ['uses'=>'AnimalController@totalAnimalsAreas']);
	Route::get('/totalanimalsbreeds', ['uses'=>'BreedController@totalAnimalsBreeds']);

	/*** Configuration Animal RFID mode SCAN ***/
	Route::get('/configid', ['uses'=>'ConfigurationController@loadID']);
	Route::post('/configid/{animal_rfid}', ['uses'=>'ConfigurationController@updateReadValue']);

	/*** Display the locations 1 belonging to the specific area. ***/
	Route::get('/showbyarea/{area_id}', ['uses'=>'Lct1Controller@showByArea']);

	/*** Display the locations 1 belonging to the specific locations 2. ***/
	Route::get('/showbylct1/{lct1_id}', ['uses'=>'Lct2Controller@showByLct1']);

	Route::get('/totalanimalvaccinations', ['uses'=>'VaccinationController@totalAnimalVaccinations']);
	Route::get('/totalanimaldewormers', ['uses'=>'DewormerController@totalAnimalDewormers']);
	Route::get('/totalanimalvitamins', ['uses'=>'VitaminController@totalAnimalVitamins']);
	Route::get('/totalanimalagegroups', ['uses'=>'AgeGroupController@totalAnimalsAgeGroups']);
});
