@extends('template')

@section('content')
	<p>{{ $breed->id }} -> {{ $breed->name }}</p>
	<hr>
	<p>
		<a href="{{ route('breeds.index') }}" class="btn-link">Regresar al listado de Razas</a>
	</p>
@endsection