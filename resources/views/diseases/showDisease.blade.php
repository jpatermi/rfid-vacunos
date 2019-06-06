@extends('template')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h3>Listado de Enfermedades
              <small class="text-danger">{{ $animal->animal_rfid }}</small>
            </h3>
            <div>
              <a class="btn btn-primary mb-2" href="{{ route('diseases.create', 'animal_id = ' . $animal->id) }}">Agregar Enfermedad</a>
              <a href="{{ route('animals.index') }}" class="btn-link btn pb-3">Regresar al listado de Animales</a>
            </div>
        </div>
      </div>
      <div class="card-body">
        @if ($animalDiseases)
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Diagnóstico</th>
                  <th scope="col">Revisión</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($animalDiseases as $animalDisease)
              <tr>
                  <th>{{ $animalDisease['diagnostic_name'] }}</th>
                  <th>{{ $animalDisease['review_date'] }}</th>
                  <td>
                      <form action="{{ route('diseases.destroy', $animalDisease['id']) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <a href="{{ route('diseases.edit', $animalDisease['id']) }}" class="btn btn-link text-primary">
                              <span class="material-icons">edit</span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar la Enfermedad: {{ $animalDisease['diagnostic_name'] }}?')">
                              <span class="material-icons">delete</span>
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <p>No existen Enfermedades registradas.</p>
        @endif
      </div>
      <div class="card-footer">
        <a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>
      </div>
    </div>
@endsection