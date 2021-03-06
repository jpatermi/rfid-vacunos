@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-end p-2">
                    <h2>Animales por Grupos Etarios</h2>
                    <div>
                      {{--<a href="{{ route('animals.index') }}" class="btn-link btn pb-3">Regresar al listado de Animales</a>--}}
                    </div>
                </div>
              </div>
              <div class="card-body">
                @if ($ageGroups)
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Grupo</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($ageGroups as $ageGroup)
                            <tr>
                                <td>{{ $ageGroup->name }}</td>
                                <td>{{ $ageGroup->animals_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-secondary font-weight-bold text-light">
                            <tr>
                                <td>Total:</td>
                                <td>{{ $ageGroups->sum('animals_count') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p>No existen Animales registrados.</p>
                @endif
              </div>
              <div class="card-footer">
                {{--<a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al listado de Animales</a>--}}
                <a href="{{ route('agegroups.AgeGroupPDF') }}" class="btn-link btn">Descargar PDF</a>
              </div>
            </div>
        </div>
    </div>
@endsection