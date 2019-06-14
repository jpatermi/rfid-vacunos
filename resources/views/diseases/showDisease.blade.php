@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h3>Listado de Enfermedades de:
              <small class="text-danger">{{ $animal->animal_rfid }}</small>
            </h3>
            <div>
              <a class="btn btn-primary mb-2" href="{{ route('diseases.create', 'animal_id = ' . $animal->id) }}">Agregar Enfermedad</a>
              <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn-link btn pb-3">Regresar al Detalle</a>
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
                  <td>{{ $animalDisease['diagnostic_name'] }}</td>
                  <td>{{ $animalDisease['review_date'] }}</td>
                  <td>
                      <form action="{{ route('diseases.destroy', $animalDisease['id']) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <a href="{{ route('diseases.edit', $animalDisease['id']) }}" class="btn btn-link text-primary">
                              <span data-toggle="tooltip" data-placement="top" title="Editar Enfermedad"><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar la Enfermedad: {{ $animalDisease['diagnostic_name'] }}?')">
                              <span data-toggle="tooltip" data-placement="top" title="Eliminar Enfermedad"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
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
        <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn-link btn">Regresar al Detalle</a>
      </div>
    </div>
@endsection