@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-end p-2">
                    <h2>Listado de {{ $labelGeneral }}s</h2>
                    <div>
                      @can($model . '.create')
                        <a class="btn btn-primary mb-2" href="{{ route($model . '.create') }}">Agregar {{ $labelGeneral }}</a>
                      @endcan
                    </div>
                </div>
              </div>
              <div class="card-body">
                @if ($varGenerals->isNotEmpty())
                    <table class="table table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Nombre</th>
                          <th scope="col">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($varGenerals as $varGeneral)
                      <tr>
                          <td>{{ $varGeneral->name }}</td>
                          <td>
                              <form action="{{ route($model . '.destroy', $varGeneral) }}" method="POST">
                                  {{ method_field('DELETE') }}
                                  @csrf
                                  @can($model . '.edit')
                                    <a href="{{ route($model . '.edit', $varGeneral) }}" class="btn btn-link text-primary">
                                        <span data-toggle="tooltip" data-placement="top" title="Editar {{ $labelGeneral }}" ><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                                    </a>
                                  @endcan
                                  @can($model . '.destroy')
                                    <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar a: {{ $varGeneral->name }}?')">
                                        <span data-toggle="tooltip" data-placement="top" title="Eliminar {{ $labelGeneral }}"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
                                    </button>
                                  @endcan
                              </form>
                          </td>
                      </tr>
                      @endforeach
                      </tbody>
                    </table>
                @else
                    <p>No existen {{ $labelGeneral }}s registradas.</p>
                @endif
              </div>
              <div class="card-footer">
                {{--<a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>--}}
                <!--Redirect::back();-->
              </div>
            </div>
        </div>
    </div>
@endsection