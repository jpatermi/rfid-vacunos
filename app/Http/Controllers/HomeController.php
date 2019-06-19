<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Breed;
use App\Animal;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //### Inventario ###
        $areas = \App\Area::withCount('animals')->get()->where("animals_count", ">", 0);
        $totalAreas = array();
        global $areaId;
        foreach ($areas as $area)
        {
            $arrNivelUno = array('nivel'        => 1,
                                 'areaLct1Lct2' => $area->name,
                                 'cantidad'     => $area->animals_count);
            $areaId = $area->id;
            $totalAreas[] = $arrNivelUno;
            /*** Al recorrer la colección de las áreas, me traigo el total de Animales por Módulo de cada área ****/
            $lct1s = \App\Lct1::withCount(['animals' => function ($query) {global $areaId; $query->where('area_id', $areaId);}])->get()->where("animals_count", ">", 0);
            foreach ($lct1s as $lct1)
            {
                $arrNivelUno = array('nivel'        => 3,
                                     'areaLct1Lct2' => $lct1->name,
                                     'cantidad'     => $lct1->animals_count);
                $totalAreas[] = $arrNivelUno;
            }
        }
        $total = Animal::count();


        //### Gráfico Razas ###
        $breeds = \App\Breed::withCount('animals')->get()->where("animals_count", ">", 0);
        $arrLabel = array();
        $arrData = array();
        $arrBackgroundColor = array();
        foreach ($breeds as $breed) {
            $arrLabel[] = $breed->name;
            $arrData[] = $breed->animals_count;
            $arrBackgroundColor[] = 'rgb(255, ' . rand(0,255) . ', ' . rand(0,255) . ')';
        }

        $chartPie = app()->chartjs
                ->name('pieChartTest')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels($arrLabel)
                ->datasets([
                    [
                        'backgroundColor' => $arrBackgroundColor,
                        'hoverBackgroundColor' => $arrBackgroundColor,
                        'data' => $arrData,
                        //'borderAlign' => 'inner'
                    ]
                ])
                ->optionsRaw([
                    'legend' => [
                        'display' => true,
                        'labels' => [
                            'fontColor' => '#000'
                        ]
                    ],
                    'title' => [
                        'display' => false,
                        'text' => 'Prueba'
                    ]/*,
                    'animation.animateScale' => 'true'*/
                ]);

        $chartPieZoom = app()->chartjs
                ->name('pieChartZoom')
                ->type('pie')
                ->size(['width' => 700, 'height' => 500])
                ->labels($arrLabel)
                ->datasets([
                    [
                        'backgroundColor' => $arrBackgroundColor,
                        'hoverBackgroundColor' => $arrBackgroundColor,
                        'data' => $arrData,
                        //'borderAlign' => 'inner'
                    ]
                ])
                ->optionsRaw([
                    'legend' => [
                        'display' => true,
                        'labels' => [
                            'fontColor' => '#000'
                        ]
                    ],
                    'title' => [
                        'display' => false,
                        'text' => 'Prueba'
                    ]/*,
                    'animation.animateScale' => 'true'*/
                ]);


// $x=['rgb(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) .')',
//     'rgb(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) .')',
//     'rgb(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) .')',
//     'rgb(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) .')',
//     'rgb(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) .')'
// ];
// $chartPie = app()->chartjs
//         ->name('pieChartTest')
//         ->type('pie')
//         ->size(['width' => 400, 'height' => 200])
//         ->labels(['Label x', 'Label y', 'Label z', 'Label A', 'Label B'])
//         ->datasets([
//             [
//                 'backgroundColor' => $x,
//                 'hoverBackgroundColor' => $x,
//                 'data' => [69, 59, 49, 39, 29]
//             ]
//         ])
//         ->options([]);



        $chartDoughnut = app()->chartjs
                ->name('doughnutChartTest')
                ->type('doughnut')
                ->size(['width' => 400, 'height' => 200])
                ->labels($arrLabel)
                ->datasets([
                    [
                        'backgroundColor' => $arrBackgroundColor,
                        'hoverBackgroundColor' => $arrBackgroundColor,
                        'data' => $arrData,
                        //'borderAlign' => 'inner'
                    ]
                ])
                ->optionsRaw([
                    'legend' => [
                        'display' => true,
                        'labels' => [
                            'fontColor' => '#000'
                        ]
                    ],
                    'title' => [
                        'display' => false,
                        'text' => 'Prueba'
                    ]/*,
                    'animation.animateScale' => 'true'*/
                ]);

        $chartDoughnutZoom = app()->chartjs
                ->name('doughnutChartZoom')
                ->type('doughnut')
                ->size(['width' => 700, 'height' => 500])
                ->labels($arrLabel)
                ->datasets([
                    [
                        'backgroundColor' => $arrBackgroundColor,
                        'hoverBackgroundColor' => $arrBackgroundColor,
                        'data' => $arrData,
                        //'borderAlign' => 'inner'
                    ]
                ])
                ->optionsRaw([
                    'legend' => [
                        'display' => true,
                        'labels' => [
                            'fontColor' => '#000'
                        ]
                    ],
                    'title' => [
                        'display' => false,
                        'text' => 'Prueba'
                    ]/*,
                    'animation.animateScale' => 'true'*/
                ]);

        $ageGroups = \App\AgeGroup::withCount('animals')->get()->where("animals_count", ">", 0);
        $arrLabel = array();
        $arrData = array();
        $arrBackgroundColor = array();
        foreach ($ageGroups as $ageGroup) {
            $arrLabel[] = $ageGroup->name;
            $arrData[] = $ageGroup->animals_count;
            $arrBackgroundColor[] = 'rgba(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) . ', 0.3)';
        }

        $chartBar = app()->chartjs
                 ->name('barChartTest')
                 ->type('bar')
                 ->size(['width' => 400, 'height' => 200])
                 ->labels($arrLabel)
                 ->datasets([
                     [
                         'label' => 'Grupo Etario',
                         'data' => $arrData,
                         //'fill' => false,
                         'backgroundColor' => $arrBackgroundColor,
                         //'borderColor' => ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(54, 162, 250)'],
                         //'borderWidth' => 1
                     ]/*,
                     [
                         "label" => "My First dataset",
                         'backgroundColor' => ['rgba(255, 99, 132, 0.3)', 'rgba(54, 162, 235, 0.3)', 'rgba(54, 162, 250, 0.3)'],
                         'borderColor' => ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(54, 162, 250)'],
                         'borderWidth' => 1,
                         'data' => [65, 12, 20]
                     ]*/
                 ])
                 ->options([]);

        $chartBarZoom = app()->chartjs
                 ->name('barChartZoom')
                 ->type('bar')
                 ->size(['width' => 700, 'height' => 500])
                 ->labels($arrLabel)
                 ->datasets([
                     [
                         'label' => 'Grupo Etario',
                         'data' => $arrData,
                         //'fill' => false,
                         'backgroundColor' => $arrBackgroundColor,
                         //'borderColor' => ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(54, 162, 250)'],
                         //'borderWidth' => 1
                     ]/*,
                     [
                         "label" => "My First dataset",
                         'backgroundColor' => ['rgba(255, 99, 132, 0.3)', 'rgba(54, 162, 235, 0.3)', 'rgba(54, 162, 250, 0.3)'],
                         'borderColor' => ['rgba(255, 99, 132)', 'rgba(54, 162, 235)', 'rgba(54, 162, 250)'],
                         'borderWidth' => 1,
                         'data' => [65, 12, 20]
                     ]*/
                 ])
                 ->options([]);

        $chartLine = app()->chartjs
                ->name('lineChartTest')
                ->type('line')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'])
                ->datasets([
                    [
                        "label" => "Calostro",
                        'backgroundColor' => "rgba(255, 159, 64, 0.2)",
                        'borderColor' => "rgba(255, 159, 64, 0.5)",
                        "pointBorderColor" => "rgba(255, 159, 64, 0.8)",
                        "pointBackgroundColor" => "rgba(255, 159, 64, 0.8)",
                        "pointHoverBackgroundColor" => "#fff",
                        "pointHoverBorderColor" => "rgba(220,220,220,1)",
                        'data' => [16, 20, 35, 34, 41, 15],
                    ],
                    [
                        "label" => "Leche",
                        'backgroundColor' => "rgba(255, 99, 132, 0.2)",
                        'borderColor' => "rgba(255, 99, 132, 0.5)",
                        "pointBorderColor" => "rgba(255, 99, 132, 0.8)",
                        "pointBackgroundColor" => "rgba(255, 99, 132, 0.8)",
                        "pointHoverBackgroundColor" => "#fff",
                        "pointHoverBorderColor" => "rgba(220,220,220,1)",
                        'data' => [12, 33, 37, 50, 40, 23],
                    ]
                ])
                ->options([]);

        $chartLineZoom = app()->chartjs
                ->name('lineChartZoom')
                ->type('line')
                ->size(['width' => 700, 'height' => 500])
                ->labels(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'])
                ->datasets([
                    [
                        "label" => "Calostro",
                        'backgroundColor' => "rgba(255, 159, 64, 0.2)",
                        'borderColor' => "rgba(255, 159, 64, 0.5)",
                        "pointBorderColor" => "rgba(255, 159, 64, 0.8)",
                        "pointBackgroundColor" => "rgba(255, 159, 64, 0.8)",
                        "pointHoverBackgroundColor" => "#fff",
                        "pointHoverBorderColor" => "rgba(220,220,220,1)",
                        'data' => [16, 20, 35, 34, 41, 15],
                    ],
                    [
                        "label" => "Leche",
                        'backgroundColor' => "rgba(255, 99, 132, 0.2)",
                        'borderColor' => "rgba(255, 99, 132, 0.5)",
                        "pointBorderColor" => "rgba(255, 99, 132, 0.8)",
                        "pointBackgroundColor" => "rgba(255, 99, 132, 0.8)",
                        "pointHoverBackgroundColor" => "#fff",
                        "pointHoverBorderColor" => "rgba(220,220,220,1)",
                        'data' => [12, 33, 37, 50, 40, 23],
                    ]
                ])
                ->options([]);



        return view('home', compact('chartPie', 'chartBar', 'chartLine', 'chartDoughnut', 'chartPieZoom', 'chartBarZoom', 'chartDoughnutZoom', 'chartLineZoom', 'totalAreas', 'total'));
    }
}
