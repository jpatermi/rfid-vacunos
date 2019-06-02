@extends('template')
@section('content')
	<div class="card">
		<h4 class="card-header">Actualizar Raza</h4>
		<div class="card-body">
			@if($errors->any())
				<div class="alert alert-danger">
					<h6>Por favor corregir los errores abajo señalados:</h6>
				</div>
			@endif

			<form method = "POST" action="{{ route('breeds.update', $breed) }}">
				{{ method_field('PUT') }}
				@csrf
				<div class="form-group">
					<label for="name">Nombre:</label>
					<input type="text" class="form-control" name="name" id="name" placeholder="Nombre Raza" value="{{ old('name', $breed->name) }}">
				</div>
				@if($errors->has('name'))
					<p class="text-danger">{{ $errors->first('name') }}</p>
				@endif
				<button type="submit" class="btn btn-primary">Actualizar Raza</button>
				<a href="{{ route('breeds.index') }}" class="btn-link">Regresar al listado de Razas</a>
			</form>
		</div>
	</div>
@endsection