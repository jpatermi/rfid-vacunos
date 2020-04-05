@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h2>Listado de {{ $labelVacDewVit }}s</h2>
            <div>
              @can($model . '.create')
                <a class="btn btn-primary mb-2" href="{{ route($model . '.create') }}">Agregar {{ $labelVacDewVit }}</a>
              @endcan
            </div>
        </div>
      </div>
      <div class="card-body">
        @if ($varVacDewVits->isNotEmpty())
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Nombre</th>
                  <th scope="col">Característica</th>
                  @if($labelVacDewVit != 'Examen')
                    <th scope="col">Fecha Vencimiento</th>
                    <th scope="col">Lote</th>
                  @endif
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($varVacDewVits as $varVacDewVit)
              <tr>
                  <td>{{ $varVacDewVit->name }}</td>
                  <td>{{ $varVacDewVit->characteristic }}</td>
                  @if($labelVacDewVit != 'Examen')
                    <td>{{ $varVacDewVit->expiration_date->format('d/m/Y') }}</td>
                    <td>{{ $varVacDewVit->lot }}</td>
                  @endif
                  <td>
                      <form action="{{ route($model . '.destroy', $varVacDewVit) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          @can($model . '.edit')
                            <a href="{{ route($model . '.edit', $varVacDewVit) }}" class="btn btn-link text-primary">
                                <span data-toggle="tooltip" data-placement="top" title="Editar {{ $labelVacDewVit }}" ><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                            </a>
                          @endcan
                          @can($model . '.destroy')
                            <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar la {{ $labelVacDewVit }}: {{ $varVacDewVit->name }}?')">
                                <span data-toggle="tooltip" data-placement="top" title="Eliminar {{ $labelVacDewVit }}"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
                            </button>
                          @endcan
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <p>No existen {{ $labelVacDewVit }} registradas.</p>
        @endif
      </div>
      <div class="card-footer">
        {{--<a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>--}}
        <!--Redirect::back();-->
      </div>
    </div>
@endsection