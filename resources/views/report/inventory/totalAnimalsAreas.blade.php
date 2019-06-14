@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-end p-2">
                    <h2>Animales por Ubicación</h2>
                    {{--<div>
                      <a href="{{ route('animals.index') }}" class="btn-link btn pb-3">Regresar al listado de Animales</a>
                    </div>--}}
                </div>
              </div>
              <div class="card-body">
                @if ($totalAreas)
                    <table class="table table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Ubicación</th>
                          <th scope="col">Cantidad</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($totalAreas as $totalArea)
                      <tr>
                          <td class="pl-{{ $totalArea['nivel'] }} @if(($totalArea['nivel'] == 1 or $totalArea['nivel'] == 3)) font-weight-bold @endif">{{ $totalArea['areaLct1Lct2'] }}</td>
                          <td class="@if(($totalArea['nivel'] == 1 or $totalArea['nivel'] == 3)) font-weight-bold @endif">{{ $totalArea['cantidad'] }}</td>
                      </tr>
                      @endforeach
                      </tbody>
                      <tfoot class="bg-secondary font-weight-bold text-light">
                          <tr>
                              <td>Total de Animales:</td>
                              <td>{{ $total }}</td>
                          </tr>
                      </tfoot>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
              </div>
              <div class="card-footer">
                {{--<a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>--}}
                <a href="{{ route('animals.InvUbicPDF') }}" class="btn-link btn">Descargar PDF</a>
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-end p-2">
                    <h2>Animales por Razas</h2>
                    <div>
                      {{--<a href="{{ route('animals.index') }}" class="btn-link btn pb-3">Regresar al listado de Animales</a>--}}
                    </div>
                </div>
              </div>
              <div class="card-body">
                @if ($breeds)
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Raza</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($breeds as $breed)
                            <tr>
                                <td>{{ $breed->name }}</td>
                                <td>{{ $breed->animals_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-secondary font-weight-bold text-light">
                            <tr>
                                <td>Total:</td>
                                <td>{{ $breeds->sum('animals_count') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
              </div>
              <div class="card-footer">
                {{--<a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>--}}
                <a href="{{ route('breeds.BreedsPDF') }}" class="btn-link btn">Descargar PDF</a>
              </div>
            </div>
        </div>
    </div>
@endsection