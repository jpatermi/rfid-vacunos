@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-end p-2">
                    <h2>{{ $labelVacDewVit }} por Animales</h2>
                    <div>
                      {{--<a href="{{ route('animals.index') }}" class="btn-link btn pb-3">Regresar al listado de Animales</a>--}}
                    </div>
                </div>
              </div>
              <div class="card-body">
                @if ($totalGenerals)
                    <table class="table table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">RFID's</th>
                          <th scope="col">Aplicación</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($totalGenerals as $totalGeneral)
                        <tr>
                            <td class="pl-{{ $totalGeneral['nivel'] }} @if($totalGeneral['nivel'] == 1) font-weight-bold @endif">{{ $totalGeneral['vaccOrfid'] }}</td>
                            <td>{{ $totalGeneral['application_date'] }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
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