@extends('layouts.app')

@section('graphic')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-header font-weight-bold">Inventario</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{--<div>
                        {!! $chartPie->render() !!}
                    </div>--}}
                    <div class="alert alert-primary" role="alert">
                        <table width="100%" class="font-weight-bold text-primary">
                            <td>Total General de Animales:</td>
                            <td class="float-right">{{ $total }}</td>
                        </table>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <table width="100%" class="font-weight-bold">
                            @foreach($totalAreas as $totalArea)
                                <tr>
                                <td class="pl-{{ $totalArea['nivel'] }}">{{ $totalArea['areaLct1Lct2'] }}</td>
                                <td class="float-right">{{ $totalArea['cantidad'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    <!--<div style="height: 63px;"></div>-->
                </div>
                {{--<div class="text-right pb-2 pr-2">
                    <div>
                        <a class="btn btn-light" href="#" data-toggle="modal" data-target="#pieChartZoomIn"><span data-toggle="tooltip" data-placement="top" title="Ampliar Gráfico"><img class="" src="{{ asset('img/ico/baseline-zoom_in-24px.svg') }}" alt="Ampliar"></span>
                        </a>
                    </div>
                </div>--}}
            </div>
            </div>
        </div>
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-header font-weight-bold">Animales por Grupo Etario</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        {!! $chartBar->render() !!}
                    </div>
                </div>
                <div class="text-right pb-2 pr-2">
                    <div>
                        <a class="btn btn-light" href="#" data-toggle="modal" data-target="#barChartZoomIn"><span data-toggle="tooltip" data-placement="top" title="Ampliar Gráfico"><img class="" src="{{ asset('img/ico/baseline-zoom_in-24px.svg') }}" alt="Ampliar"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-header font-weight-bold">Producción Mensual</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        {!! $chartLine->render() !!}
                    </div>
                </div>
                <div class="text-right pb-2 pr-2">
                    <div>
                        <a class="btn btn-light" href="#" data-toggle="modal" data-target="#lineChartZoomIn"><span data-toggle="tooltip" data-placement="top" title="Ampliar Gráfico"><img class="" src="{{ asset('img/ico/baseline-zoom_in-24px.svg') }}" alt="Ampliar"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-header font-weight-bold">Animales por Raza</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        {!! $chartDoughnut->render() !!}
                    </div>
                </div>
                <div class="text-right pb-2 pr-2">
                    <div>
                        <a class="btn btn-light" href="#" data-toggle="modal" data-target="#doughnutChartZoomIn"><span data-toggle="tooltip" data-placement="top" title="Ampliar Gráfico"><img class="" src="{{ asset('img/ico/baseline-zoom_in-24px.svg') }}" alt="Ampliar"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('graphics.pieChart')
@include('graphics.barChart')
@include('graphics.doughnutChart')
@include('graphics.lineChart')
@endsection


