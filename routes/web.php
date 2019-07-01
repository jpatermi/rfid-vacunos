<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    return view('/prueba/prueba');
});

Auth::routes();

Route::group(['middleware' => ['auth.web']], function() use($router) {
	/*** Razas ***/
	Route::resource('breeds', 'BreedController');

	/*** Animales ***/
	Route::resource('animals', 'AnimalController');

	/*** Características físicas del Animal ***/
	Route::resource('physicalCharacteristics', 'PhysicalCharacteristicController');

	/*** Vacunas del Animal ***/
	Route::resource('animalvaccination', 'AnimalVaccinationController');

	/*** Desparasitaciones del Animal ***/
	Route::resource('animaldewormer', 'AnimalDewormerController');

	// ### Vitaminas del Animal ###
	Route::resource('animalvitamin', 'AnimalVitaminController');

	// ### Exámenes para el Animal ###
	Route::resource('animalexamn', 'AnimalExamnController');

	// ### Enfermedades del Animal ###
	Route::get('/animaldiseases/{animal_rfid}', ['uses'=>'DiseaseController@getAnimalDiseases'])->name('disease.GetAnimalDiseases');
	Route::resource('diseases', 'DiseaseController');

	// ### Producción del Animal ###
	Route::resource('productions', 'ProductionController');

	// ### Vacunas, Desparasitante y Vitaminas para el Animal ###
	Route::resource('vaccinations', 'VaccinationController');
	Route::resource('dewormers', 'DewormerController');
	Route::resource('vitamins', 'VitaminController');
	Route::resource('examns', 'ExamnController');

	//### Generales Grupo Etario, Veterinarios, Diagnósticos, Causas, Tratamientos y Responsables
	Route::resource('agegroups', 'AgeGroupController');
	Route::resource('veterinarians', 'VeterinarianController');
	Route::resource('diagnostics', 'DiagnosticController');
	Route::resource('causes', 'CauseController');
	Route::resource('treatments', 'TreatmentController');
	Route::resource('responsibles', 'ResponsibleController');

	//### Ubicaciones ###
	Route::resource('areas', 'AreaController');
	Route::resource('farms', 'FarmController');
	Route::resource('lct1s', 'Lct1Controller');
	Route::resource('lct2s', 'Lct2Controller');

	//### Users ###
	Route::resource('users', 'UsersController');

	/*** Ubicaciones por Área y Módulo***/
	Route::get('/showbyarea/{area}', ['uses'=>'Lct1Controller@showByArea'])->name('lct1s.showByArea');
	Route::get('/showbylct1/{lct1}', ['uses'=>'Lct2Controller@showByLct1'])->name('lct2s.showByLct1');

	//### Home ###
	Route::get('/home', 'HomeController@index')->name('home')->middleware('auth.web');

	//### Inventario ###
	Route::get('/totalanimalsareas', ['uses'=>'AnimalController@totalAnimalsAreas'])->name('animals.totalanimalsareas');

	// ### Reportes de Vacunas, Desparasitantes, Vutaminas, Exámenes y Grupos Etarios por Animal
	Route::get('/totalanimalvaccinations', ['uses'=>'VaccinationController@totalAnimalVaccinations'])->name('vaccinations.totalAnimalVaccinations');
	Route::get('/totalanimaldewormers', ['uses'=>'DewormerController@totalAnimalDewormers'])->name('dewormers.totalAnimalDewormers');
	Route::get('/totalanimalvitamins', ['uses'=>'VitaminController@totalAnimalVitamins'])->name('vitamins.totalAnimalVitamins');
	Route::get('/totalAnimalExamns', ['uses'=>'ExamnController@totalAnimalExamns'])->name('examns.totalAnimalExamns');
	Route::get('/totalanimalagegroups', ['uses'=>'AgeGroupController@totalAnimalsAgeGroups'])->name('agegroups.totalAnimalsAgeGroups');

	//### Históricos de Pesos y Alturas ###
	Route::resource('historicals', 'HistoricalWeightHeightController');

	//### Rutas para los PDF ###
	Route::get('/agegrouppdf', ['uses'=>'AgeGroupController@exportPdf'])->name('agegroups.AgeGroupPDF');
	Route::get('/vaccinationpdf', ['uses'=>'VaccinationController@exportPdf'])->name('vaccinations.VaccinationPDF');
	Route::get('/dewormerpdf', ['uses'=>'DewormerController@exportPdf'])->name('dewormers.DewormerPDF');
	Route::get('/vitaminpdf', ['uses'=>'VitaminController@exportPdf'])->name('vitamins.VitaminPDF');
	Route::get('/examnpdf', ['uses'=>'ExamnController@exportPdf'])->name('examns.ExamnPDF');
	Route::get('/invubicspdf', ['uses'=>'AnimalController@exportPdf'])->name('animals.InvUbicPDF');
	Route::get('/invbreedspdf', ['uses'=>'BreedController@exportPdf'])->name('breeds.BreedsPDF');
});
