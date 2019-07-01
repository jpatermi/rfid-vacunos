@extends('layouts.app')
@section('content')
	<div class="card">
		<h4 class="card-header font-weight-bold text-center">Agregar Animal</h4>
		<div class="card-body">

			@if($errors->any())
				<div class="alert alert-danger text-center">
					<h6 class="font-weight-bold">Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route('animals.store') }}">
				@csrf
				<div class="form-row mb-4 nav justify-content-center">
				    <div class="form-group col-md-4">
						<label class="font-weight-bold nav justify-content-center text-primary" for="animal_rfid">RFID:</label>
						<input type="text" class="form-control text-center font-weight-bold text-primary border border-primary" name="animal_rfid" id="animal_rfid" placeholder="0000000000" maxlength="10" minlength="10" autocomplete="off" value="{{ old('animal_rfid') }}">
						@if($errors->has('animal_rfid'))
							<p class="text-danger"><strong>{{ $errors->first('animal_rfid') }}</strong></p>
						@endif
					</div>
				</div>

				<hr class="pb-4">

				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="gender">Género:</label>
				    	<select class="form-control" id="gender" name="gender">
				      		<option value="M">Macho</option>
				      		<option value="H">Hembra</option>
				    	</select>
					</div>
	                <div class="form-group col-md-4">
	                    <label class="font-weight-bold" for="dateface">Fecha de nacimiento:</label>
	                    <div class="input-group date">
	                        <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface') }}">
	                          <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
	                    </div>
						@if($errors->has('birthdate'))
							<p class="text-danger"><strong>{{ $errors->first('birthdate') }}</strong></p>
						@endif
	                </div>
				</div>

				<div class="form-row nav justify-content-center">
				    <div class="form-group col-md-4">
						<label class="font-weight-bold" for="mother_rfid">RFID Madre:</label>
						<input type="text" class="form-control" name="mother_rfid" id="mother_rfid" placeholder="RFID o ID" maxlength="50" value="{{ old('mother_rfid') }}">
						@if($errors->has('mother_rfid'))
							<p class="text-danger"><strong>{{ $errors->first('mother_rfid') }}</strong></p>
						@endif
					</div>
				    <div class="form-group col-md-4">
						<label class="font-weight-bold" for="father_rfid">RFID Padre:</label>
						<input type="text" class="form-control" name="father_rfid" id="father_rfid" placeholder="RFID o ID" maxlength="50" value="{{ old('father_rfid') }}">
						@if($errors->has('father_rfid'))
							<p class="text-danger"><strong>{{ $errors->first('father_rfid') }}</strong></p>
						@endif
				    </div>
				</div>

				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="breed_id">Raza:</label>
				    	<select class="form-control" id="breed_id" name="breed_id">
				    		@foreach($breeds as $breed)
				      			<option value="{{ $breed->id }}">{{ $breed->name }}</option>
				      		@endforeach
				    	</select>
						@if($errors->has('breed_id'))
							<p class="text-danger"><strong>{{ $errors->first('breed_id') }}</strong></p>
						@endif
					</div>
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="age_group_id">Grupo Etario:</label>
				    	<select class="form-control" id="age_group_id" name="age_group_id">
				    		@foreach($ageGroups as $ageGroup)
				      			<option value="{{ $ageGroup->id }}">{{ $ageGroup->name }}</option>
				      		@endforeach
				    	</select>
						@if($errors->has('age_group_id'))
							<p class="text-danger"><strong>{{ $errors->first('age_group_id') }}</strong></p>
						@endif
					</div>
				</div>

				<div class="form-row nav justify-content-center">
				    <div class="form-group col-md-4">
						<label class="font-weight-bold" for="last_weight">Peso (Kg):</label>
						<input type="text" class="form-control" name="last_weight" id="last_weight" placeholder="0.00" value="{{ old('last_weight') }}">
						@if($errors->has('last_weight'))
							<p class="text-danger"><strong>{{ $errors->first('last_weight') }}</strong></p>
						@endif
				    </div>
				    <div class="form-group col-md-4">
						<label class="font-weight-bold" for="last_height">Atura (Cm):</label>
						<input type="text" class="form-control" name="last_height" id="last_height" placeholder="000" value="{{ old('last_height', 0) }}">
						@if($errors->has('last_height'))
							<p class="text-danger"><strong>{{ $errors->first('last_height') }}</strong></p>
						@endif
				    </div>
				</div>

				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="area_id">Área:</label>
				    	<select class="form-control" id="area" name="area_id">
				    		@foreach($areas as $area)
				      			<option value="{{ $area->id }}">{{ $area->name }}</option>
				      		@endforeach
				    	</select>
						@if($errors->has('area_id'))
							<p class="text-danger"><strong>{{ $errors->first('area_id') }}</strong></p>
						@endif
				    </div>
				</div>

				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="lct1_id">Ubicación UNO:</label>
				    	<select class="form-control" id="lct1" name="lct1_id">
				    	</select>
						@if($errors->has('lct1_id'))
							<p class="text-danger"><strong>{{ $errors->first('lct1_id') }}</strong></p>
						@endif
					</div>
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="lct2_id">Ubicación DOS:</label>
				    	<select class="form-control" id="lct2" name="lct2_id">
				    	</select>
						@if($errors->has('lct2_id'))
							<p class="text-danger"><strong>{{ $errors->first('lct2_id') }}</strong></p>
						@endif
					</div>
				</div>

				<!-- Textos ocultos que  vendrán en la sesión para el user_id y el farm_id -->
				<input type="text" name="farm_id" id="farm_id" hidden value=1>
				<input type="text" name="user_id" id="user_id" hidden value="{{ session('user_id') }}">
				<input type="text" name="birthdate" id="birthdate" hidden value="{{ old('birthdate') }}">
				<!-- -->

				<div class="nav justify-content-center">
			  		<button type="submit" class="col-md-2 btn btn-primary">Agregar Animal</button>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<a href="{{ route('animals.index') }}" class="btn btn-link">Regresar al listado de Animales</a>
		</div>
	</div>
@endsection

@section('scripts')
    <script>
		$.when( $.ready ).then(function() {
  			$( "#area" ).trigger( "change" );
		});

		$("#area").change(event => {
			$("#lct1").empty();
			$.getJSON(`/rfid/public/showbyarea/${event.target.value}`).done(function(response, state){
				response.forEach(element => {
				$("#lct1").append(`<option value=${element.id}>${element.name}</option>}`)
				});
				$( "#lct1" ).trigger( "change" );
			});
		});

		$("#lct1").change(event => {
			$("#lct2").empty();
			$.get(`/rfid/public/showbylct1/${lct1.value}`, function(response, state){
				response.forEach(element => {
				$("#lct2").append(`<option value=${element.id}>${element.name}</option>}`)
				});
			});
		});

        $("#dateface").change(function(event) {
          $( "#birthdate" ).attr( "value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
        });

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
    </script>
@endsection
