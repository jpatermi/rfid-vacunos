@extends('template')

@section('content')
	<p>{{ $breed->id }} -> {{ $breed->name }}</p>
	<hr>
	<p>
		<a href="{{ route($model . '.index') }}" class="btn-link">Regresar al listado de Razas</a>
	</p>
@endsection