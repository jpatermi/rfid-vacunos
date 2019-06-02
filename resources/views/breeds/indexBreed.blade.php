@extends('template')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h2>Listado de Razas</h2>
            <a class="btn btn-primary mb-2" href="{{ route('breeds.create') }}">Agregar Raza</a>
        </div>
      </div>
      <div class="card-body">
        @if ($breeds->isNotEmpty())
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Nombre</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($breeds as $breed)
              <tr>
                  <th>{{ $breed->name }}</th>
                  <td>
                      <form action="{{ route('breeds.destroy', $breed) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <a href="{{ route('breeds.show', $breed) }}" class="btn btn-link text-primary">
                              <span class="material-icons">remove_red_eye</span>
                          </a>
                          <a href="{{ route('breeds.edit', $breed) }}" class="btn btn-link text-primary">
                              <span class="material-icons">edit</span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar la Raza: {{ $breed->name }}?')">
                              <span class="material-icons">delete</span>
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <p>No existen Razas registradas.</p>
        @endif
      </div>
    </div>
@endsection