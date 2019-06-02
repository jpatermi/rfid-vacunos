@extends('template')
@section('content')
	<div class="card">
		<h4 class="card-header font-weight-bold text-center">Actualizar Animal</h4>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger text-center">
					<h6 class="font-weight-bold">Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route('animals.update', $animal) }}" enctype="multipart/form-data">
				{{ method_field('PUT') }}
				@csrf
				<div class="form-row nav justify-content-center">
				    <div class="form-group col-md-4">
						<label class="font-weight-bold nav justify-content-center text-dark" for="animal_rfid">RFID:</label>
						<input type="text" class="form-control text-center font-weight-bold text-danger border border-dark" name="animal_rfid" id="animal_rfid" placeholder="0000000000" maxlength="10" minlength="10" readonly value="{{ old('animal_rfid', $animal->animal_rfid) }}">
						@if($errors->has('animal_rfid'))
							<p class="text-danger"><strong>{{ $errors->first('animal_rfid') }}</strong></p>
						@endif
				    </div>
				</div>
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
				<div class="form-row nav justify-content-center mb-2">
					<div class="custom-file col-md-8">
						<input type="file" class="custom-file-input" id="photo" name="photo" accept="image/jpeg">
						<label class="custom-file-label" for="photo">Seleccionar Foto</label>
					</div>
				</div>
				<hr class="mt-4">
				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="gender">Género:</label>
				    	<select class="form-control" id="gender" name="gender">
				      		<option value="M" @if($animal->gender == 'M') selected @endif>Macho</option>
				      		<option value="H" @if($animal->gender == 'H') selected @endif>Hembra</option>
				    	</select>
					</div>
					<div class="form-group col-md-4">
	                    <label class="font-weight-bold" for="dateface">Fecha de nacimiento:</label>
	                    <div class="input-group date">
	                        <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface', $animal->birthdate) }}">
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
						<input type="text" class="form-control" name="mother_rfid" id="mother_rfid" placeholder="0000000000" maxlength="10" minlength="10" value="{{ old('mother_rfid', $animal->mother_rfid) }}">
						@if($errors->has('mother_rfid'))
							<p class="text-danger"><strong>{{ $errors->first('mother_rfid') }}</strong></p>
						@endif
					</div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold" for="father_rfid">RFID Padre:</label>
						<input type="text" class="form-control" name="father_rfid" id="father_rfid" placeholder="0000000000" maxlength="10" minlength="10" value="{{ old('father_rfid', $animal->father_rfid) }}">
						@if($errors->has('father_rfid'))
							<p class="text-danger"><strong>{{ $errors->first('father_rfid') }}</strong></p>
						@endif
				    </div>
				</div>
				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="breed_id">Razas:</label>
				    	<select class="form-control" id="breed_id" name="breed_id">
				    		@foreach($breeds as $breed)
				      			<option value="{{ $breed->id }}" @if($animal->breed_id == $breed->id) selected @endif>
				      				{{ $breed->name }}
				      			</option>
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
				      			<option value="{{ $ageGroup->id }}" @if($animal->age_group_id == $ageGroup->id) selected @endif>
				      				{{ $ageGroup->name }}
				      			</option>
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
						<input type="text" class="form-control" name="last_weight" id="last_weight" placeholder="0.00" value="{{ old('last_weight', $animal->last_weight) }}" readonly data-toggle="tooltip" data-placement="top" title="Sólo se puede editar por el hístorico">
						@if($errors->has('last_weight'))
							<p class="text-danger"><strong>{{ $errors->first('last_weight') }}</strong></p>
						@endif
				    </div>
					<div class="form-group col-md-4">
						<label class="font-weight-bold" for="last_height">Atura (Cm):</label>
						<input type="text" class="form-control" name="last_height" id="last_height" placeholder="000" value="{{ old('last_height', $animal->last_height) }}" readonly data-toggle="tooltip" data-placement="top" title="Sólo se puede editar por el hístorico">
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
				      			<option value="{{ $area->id }}" @if($animal->area_id == $area->id) selected @endif>
				      				{{ $area->name }}
				      			</option>
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
				    		@foreach($lct1s as $lct1)
				      			<option value="{{ $lct1->id }}" @if($animal->lct1_id == $lct1->id) selected @endif>
				      				{{ $lct1->name }}
				      			</option>
				      		@endforeach
				    	</select>
						@if($errors->has('lct1_id'))
							<p class="text-danger"><strong>{{ $errors->first('lct1_id') }}</strong></p>
						@endif
					</div>
					<div class="form-group col-md-4">
				    	<label class="font-weight-bold" for="lct2_id">Ubicación DOS:</label>
				    	<select class="form-control" id="lct2" name="lct2_id">
				    		@foreach($lct2s as $lct2)
				      			<option value="{{ $lct2->id }}" @if($animal->lct2_id == $lct2->id) selected @endif>
				      				{{ $lct2->name }}
				      			</option>
				      		@endforeach
				    	</select>
						@if($errors->has('lct2_id'))
							<p class="text-danger"><strong>{{ $errors->first('lct2_id') }}</strong></p>
						@endif
					</div>
				</div>
				<!-- Textos ocultos que  vendrán en la sesión para el user_id y el farm_id -->
				<input type="text" name="farm_id" id="farm_id" hidden value=1>
				<input type="text" name="user_id" id="user_id" hidden value=2>
				<input type="text" name="birthdate" id="birthdate" hidden value="{{ old('birthdate', $animal->birthdate) }}">
				<!-- -->
				<div class="nav justify-content-center">
			  		<button type="submit" class="col-md-2 btn btn-primary">Actualizar Animal</button>
			  		<a href="#" class="btn btn-primary ml-2" data-toggle="modal" data-target="#show">Características Físicas</a>
					<a href="{{ route('animals.index') }}" class="btn btn-link">Regresar al listado de Animales</a>
				</div>
			</form>
		</div>
	</div>
@include('physicalCharacteristics.showPhysicalCharacteristics')
@endsection
@section('script_area_lct1_lct2')
    <script>
		$.when( $.ready ).then(function() {
          $( "#dateface" ).attr( "value", convertDateFormatUSA_to_VZLA(`${dateface.value}`));
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

        function convertDateFormatUSA_to_VZLA(string) {
          var info = string.split('-');
          return info[2] + '/' + info[1] + '/' + info[0];
        };

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
    </script>
@endsection
