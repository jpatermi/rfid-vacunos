@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
            <h2>Listado de {{ $labelAreaLct1Lct2 }}</h2>
            <div>
              <a class="btn btn-primary mb-2" href="{{ route($model . '.create') }}">Agregar {{ $labelAreaLct1Lct2 }}</a>
              {{--<a href="{{ route('animals.index') }}" class="btn-link btn pb-3">Regresar al listado de Animales</a>--}}
            </div>
        </div>
      </div>
      <div class="card-body">
        @if ($arrAreaLct1Lct2s)
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  @if($model == 'lct2s')
                    <th scope="col">Área</th>
                  @endif
                  <th scope="col">{{ $labelNameSup }}</th>
                  <th scope="col">{{ $labelAreaLct1Lct2 }}</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($arrAreaLct1Lct2s as $arrAreaLct1Lct2)
              <tr>
                  @if($model == 'lct2s')
                      <td>{{ $arrAreaLct1Lct2['nameSupSup'] }}</td>
                  @endif
                  <td>{{ $arrAreaLct1Lct2['nameSup'] }}</td>
                  <td>{{ $arrAreaLct1Lct2['name'] }}</td>
                  <td>
                      <form action="{{ route($model . '.destroy', $arrAreaLct1Lct2['id']) }}" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <a href="{{ route($model . '.edit', $arrAreaLct1Lct2['id']) }}" class="btn btn-link text-primary">
                              <span data-toggle="tooltip" data-placement="top" title="Editar {{ $labelAreaLct1Lct2 }}" ><img class="" src="{{ asset('img/ico/baseline-edit-24px.svg') }}" alt="Editar"></span>
                          </a>
                          <button type="submit" class="btn btn-link text-primary" onclick="return confirm('¿Esta seguro de eliminar la {{ $labelAreaLct1Lct2 }}: {{ $arrAreaLct1Lct2['name'] }}?')">
                              <span data-toggle="tooltip" data-placement="top" title="Eliminar {{ $labelAreaLct1Lct2 }}"><img class="" src="{{ asset('img/ico/baseline-delete-24px.svg') }}" alt="Eliminar"></span>
                          </button>
                      </form>
                  </td>
              </tr>
              @endforeach
              </tbody>
            </table>
        @else
            <p>No existen {{ $labelAreaLct1Lct2 }} registrados.</p>
        @endif
      </div>
      <div class="card-footer">
        {{--<a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>--}}
        <!--Redirect::back();-->
      </div>
    </div>
@endsection