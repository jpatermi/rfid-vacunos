@extends('layouts.app')
@section('content')
	<div class="card">
		<div class="card-header">
			<div class="nav justify-content-center">
				<h4>Actualizar {{ $labelVacDewVit }}</h4>
			</div>
		</div>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger">
					<h6>Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route($model . '.update', $varVacDewVit) }}">
				{{ method_field('PUT') }}
				@csrf
				<div class="form-row nav justify-content-center">
					<div class="form-group col-md-8">
						<label for="name">Nombre:</label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Nombre {{ $labelVacDewVit }}" value="{{ old('name', $varVacDewVit->name) }}">
						@if($errors->has('name'))
							<p class="text-danger"><strong>{{ $errors->first('name') }}</strong></p>
						@endif
					</div>
					<div class="form-group col-md-8">
						<label for="characteristic">Característica:</label>
						<input type="text" class="form-control" name="characteristic" id="characteristic" placeholder="Característica {{ $labelVacDewVit }}" value="{{ old('characteristic', $varVacDewVit->characteristic) }}">
						@if($errors->has('characteristic'))
							<p class="text-danger"><strong>{{ $errors->first('characteristic') }}</strong></p>
						@endif
					</div>
				</div>
				<div class="form-row nav justify-content-center">
					<button type="submit" class="btn btn-primary">Actualizar {{ $labelVacDewVit }}</button>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<a href="{{ route($model . '.index') }}" class="btn-link btn">Regresar al listado de {{ $labelVacDewVit }}s</a>
		</div>
	</div>
@endsection