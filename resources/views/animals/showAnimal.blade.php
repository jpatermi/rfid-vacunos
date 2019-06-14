@extends('layouts.app')
@section('content')
	<div class="card">
			<div class="bg-light pt-3">
				<h3 class="font-weight-bold text-center">Detalles del Animal:
					<small class="text-danger font-weight-bold">{{ $animal->animal_rfid }}</small>
				</h3>
			</div>

			<nav class="card-header navbar navbar-expand-md navbar-light bg-light navbar-laravel sticky-top">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav">
                    	<a href="#" class="nav-item nav-link active text-primary" data-toggle="modal" data-target="#show">Características Físicas</a>
	                    <li class="nav-item dropdown active">
	                        <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                          Registro Sanitario
	                        </a>
	                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
	                            <a class="dropdown-item" href="{{ route('animalvaccination.show', $animal) }}">Vacunas</a>
	                            <a class="dropdown-item" href="{{ route('animaldewormer.show', $animal) }}">Desparasitaciones</a>
	                            <a class="dropdown-item" href="{{ route('animalvitamin.show', $animal) }}">Vitaminas</a>
	                            <a class="dropdown-item" href="{{ route('animalexamn.show', $animal) }}">Exámenes</a>
	                            <a class="dropdown-item" href="{{ route('disease.GetAnimalDiseases', $animal) }}">Enfermedades</a>
	                            <a class="dropdown-item" href="{{ route('historicals.show', $animal) }}">Históricos de Peso y Altura</a>
	                        </div>
	                    </li>
	                    <a class="nav-item nav-link active text-primary" href="{{ route('productions.show', $animal) }}">Registro de Producción</a>
	                    <div class="ml-5">
	                    	<a href="{{ route('animals.index') }}" class="btn btn-link text-info">Regresar al listado de Animales</a>
	                    </div>
                    </ul>
                </div>
			</nav>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger">
					<h6 class="font-weight-bold">Por favor corregir los errores señalados:</h6>
					<ul>
						<li>
							@foreach($errors->all() as $error)
                				<p class="text-danger"><strong>{{ $error }}</strong></p>
              			  	@endforeach
						</li>
					</ul>
				</div>
			@endif

			@if( $animal->photo )
				<div class="text-center mb-4">
					<img src="{{ asset('/storage/photo/' . $animal->animal_rfid . '.jpg') }}" alt="Foto Animal" class="img-thumbnail" width="300px" height="300px">
				</div>
			@else
				<div class="form-row nav justify-content-center mb-2">
					<div class="col-md-4 text-center p-3 bg-light text-dark">
						<h3>Sin Foto</h3>
					</div>
				</div>
			@endif

			<hr class="mt-4">

			<div class="form-row nav justify-content-center">
				<div class="form-group col-md-4">
			    	<label class="font-weight-bold" for="gender">Género:</label>
			    	<input type="text" class="form-control" name="gender" id="gender" value=@if($animal->gender == 'M') Macho @else Hembra @endif readonly>
				</div>

				<div class="form-group col-md-4">
                    <label class="font-weight-bold" for="dateface">Fecha de nacimiento:</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="dateface" id="dateface" value="{{ $animal->birthdate }}" readonly>
                          <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
                    </div>
                </div>
            </div>

			<div class="form-row nav justify-content-center">
				<div class="form-group col-md-4">
					<label class="font-weight-bold" for="mother_rfid">RFID Madre:</label>
					<input type="text" class="form-control" name="mother_rfid" id="mother_rfid" value="{{ $animal->mother_rfid }}" readonly>
				</div>

				<div class="form-group col-md-4">
					<label class="font-weight-bold" for="father_rfid">RFID Padre:</label>
					<input type="text" class="form-control" name="father_rfid" id="father_rfid" value="{{ $animal->father_rfid }}" readonly>
			    </div>
			</div>

			<div class="form-row nav justify-content-center">
				<div class="form-group col-md-4">
			    	<label class="font-weight-bold" for="breed_id">Razas:</label>
			    	<input type="text" class="form-control" name="breed_id" id="breed_id" value="{{ $animal->breed->name }}" readonly>
				</div>

				<div class="form-group col-md-4">
			    	<label class="font-weight-bold" for="age_group_id">Grupo Etario:</label>
			    	<input type="text" class="form-control" name="age_group_id" id="age_group_id" value="{{ $animal->agegroup->name }}" readonly>
				</div>
			</div>

			<div class="form-row nav justify-content-center">
				<div class="form-group col-md-4">
					<label class="font-weight-bold" for="last_weight">Peso (Kg):</label>
					<input type="text" class="form-control" name="last_weight" id="last_weight" value="{{ $animal->last_weight }}" readonly>
			    </div>

				<div class="form-group col-md-4">
					<label class="font-weight-bold" for="last_height">Atura (Cm):</label>
					<input type="text" class="form-control" name="last_height" id="last_height" value="{{ $animal->last_height }}" readonly>
			    </div>
			</div>

			<div class="form-row nav justify-content-center">
				<div class="form-group col-md-4">
			    	<label class="font-weight-bold" for="area_id">Área:</label>
			    	<input type="text" class="form-control" name="area_id" id="area_id" value="{{ $animal->area->name }}" readonly>
			    </div>
			</div>

			<div class="form-row nav justify-content-center">
				<div class="form-group col-md-4">
			    	<label class="font-weight-bold" for="lct1_id">Ubicación UNO:</label>
			    	<input type="text" class="form-control" name="lct1_id" id="lct1_id" value="{{ $animal->lct1->name }}" readonly>
				</div>

				<div class="form-group col-md-4">
			    	<label class="font-weight-bold" for="lct2_id">Ubicación DOS:</label>
			    	<input type="text" class="form-control" name="lct2_id" id="lct2_id" value="{{ $animal->lct2->name }}" readonly>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<a href="{{ route('animals.index') }}" class="btn btn-link">Regresar al listado de Animales</a>
		</div>
	</div>
@include('physicalCharacteristics.showPhysicalCharacteristics')
@endsection
@section('scripts')
    <script>
		$.when( $.ready ).then(function() {
          $( "#dateface" ).attr( "value", convertDateFormatUSA_to_VZLA(`${dateface.value}`));
		});

        function convertDateFormatUSA_to_VZLA(string) {
          var info = string.split('-');
          return info[2] + '/' + info[1] + '/' + info[0];
        };
    </script>
@endsection
