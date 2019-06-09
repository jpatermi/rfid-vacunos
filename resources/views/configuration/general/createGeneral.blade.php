@extends('layouts.app')
@section('content')
	<div class="card">
		<div class="card-header">
			<div class="nav justify-content-center">
				<h4>Agregar {{ $labelGeneral }}</h4>
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
						<input type="text" class="form-control" name="name" id="name" placeholder="Nombre {{ $labelGeneral }}" value="{{ old('name') }}">
						@if($errors->has('name'))
							<p class="text-danger"><strong>{{ $errors->first('name') }}</strong></p>
						@endif
				    </div>
				</div>
				<div class="form-row nav justify-content-center">
					<button type="submit" class="btn btn-primary">Agregar {{ $labelGeneral }}</button>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<a href="{{ route($model . '.index') }}" class="btn-link btn">Regresar al listado de {{ $labelGeneral }}s</a>
		</div>
	</div>
@endsection