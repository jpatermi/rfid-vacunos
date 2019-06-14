@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h3>Listado de Históricos de:
              <small class="text-danger">{{ $animal->animal_rfid }}</small>
            </h3>
            <div>
              <a class="btn btn-primary mb-2" href="{{ route('historicals.create', 'animal_id = ' . $animal->id) }}">Agregar Histórico</a>
              <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn-link btn pb-3">Regresar al Detalle</a>
            </div>
        </div>
      </div>
      <div class="card-body">
        @if ($applications)
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Fecha</th>
                  <th scope="col">Peso (Kgs)</th>
                  <th scope="col">Altura (Cms)</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($applications as $application)
              <tr>
                  <td>{{ $application['measurement_date'] }}</td>
                  <td>{{ $application['weight'] }}</td>
                  <td>{{ $application['height'] }}</td>
                  <td>
                      <form action="{{ route('historicals.destroy', $application['id']) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <a href="{{ route('historicals.edit', $application['id']) }}" class="btn btn-link text-primary">
                              <span data-toggle="tooltip" data-placement="top" title="Editar Histórico" ><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar el Histórico de fecha: {{ $application['measurement_date'] }}?')">
                              <span data-toggle="tooltip" data-placement="top" title="Eliminar Histórico"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <p>No existen Históricos registrados.</p>
        @endif
      </div>
      <div class="card-footer">
        <a href="{{ route('animals.show', $animal->animal_rfid) }}" class="btn-link btn">Regresar al Detalle</a>
      </div>
    </div>
@endsection