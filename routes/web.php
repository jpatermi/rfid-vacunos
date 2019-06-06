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

// Route::get('/api/animals', function () {
//     return view('/animals/animals');
// });

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

// ### Enfermedades del Animal ###
Route::get('/animaldiseases/{animal_rfid}', ['uses'=>'DiseaseController@getAnimalDiseases'])->name('disease.GetAnimalDiseases');
Route::resource('diseases', 'DiseaseController');

/*** Ubicaciones ***/
Route::get('/showbyarea/{area}', ['uses'=>'Lct1Controller@showByArea'])->name('lct1s.showByArea');
Route::get('/showbylct1/{lct1}', ['uses'=>'Lct2Controller@showByLct1'])->name('lct2s.showByLct1');
//Route::get('/areas', ['uses'=>'Lct1Controller@index']);

//Route::post('/photo/{animal_rfid}', ['uses'=>'AnimalController@uploadPhoto'])->name('animals.uploadPhoto');

