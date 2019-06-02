@extends('template')
@section('content')
	<div class="card">
		<h4 class="card-header">Crear Raza</h4>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger">
					<h6 class="font-weight-bold">Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route('breeds.store') }}" Content-Type = "application/json">
				@csrf
			    <div class="form-group">
					<label for="name">Nombre:</label>
					<input type="text" class="form-control" name="name" id="name" placeholder="Nombre Raza" value="{{ old('name') }}">
			    </div>
				@if($errors->has('name'))
					<p class="text-danger"><strong>{{ $errors->first('name') }}</strong></p>
				@endif
				<button type="submit" class="btn btn-primary">Crear Raza</button>
				<a href="{{ route('breeds.index') }}" class="btn-link">Regresar al listado de Razas</a>
			</form>
		</div>
	</div>
@endsection