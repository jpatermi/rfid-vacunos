@extends('layouts.app')
@section('content')
	<div class="card">
		<div class="card-header">
			<div class="nav justify-content-center">
				<h4>Agregar {{ $labelAreaLct1Lct2 }}</h4>
			</div>
		</div>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger">
					<h6 class="font-weight-bold">Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route($model . '.store') }}">
				@csrf
				<div class="form-row nav justify-content-center">
				    <div class="form-group col-md-8">
						<label for="name">Nombre:</label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Nombre {{ $labelAreaLct1Lct2 }}" value="{{ old('name') }}">
						@if($errors->has('name'))
							<p class="text-danger"><strong>{{ $errors->first('name') }}</strong></p>
						@endif
				    </div>
				    <div class="form-group col-md-8">
						<label for="{{ $fieldNameSup }}">{{ $labelNameSup }}:</label>
				    	<select class="form-control" id="{{ $fieldNameSup }}" name="{{ $fieldNameSup }}">
				    		@if($model == 'lct2s')
					    		@foreach($varComboSups as $varComboSup)
					      			<option value="{{ $varComboSup['id'] }}">{{ $varComboSup['name'] }}</option>
					      		@endforeach
				    		@else
					    		@foreach($varComboSups as $varComboSup)
					      			<option value="{{ $varComboSup->id }}">{{ $varComboSup->name }}</option>
					      		@endforeach
					      	@endif
				    	</select>
						@if($errors->has($fieldNameSup))
							<p class="text-danger"><strong>{{ $errors->first($fieldNameSup) }}</strong></p>
						@endif
				    </div>
				</div>
				<div class="form-row nav justify-content-center">
					<button type="submit" class="btn btn-primary">Agregar {{ $labelAreaLct1Lct2 }}</button>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<a href="{{ route($model . '.index') }}" class="btn-link btn">Regresar al listado de {{ $labelAreaLct1Lct2 }}</a>
		</div>
	</div>
@endsection