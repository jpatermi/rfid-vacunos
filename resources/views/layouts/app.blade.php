<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Datepicker Files -->
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker.standalone.css')}}">

    <link rel="icon" href="{{ asset('img/cow-256.png') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-laravel fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/cow-256.png') }}" width="30" height="30" class="d-inline-block align-top" alt="Logo RFID">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav mr-auto text-center">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            {{--@if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif--}}
                        @else
                            <a class="nav-item nav-link active" href="{{ route('animals.index') }}">Registro de Animales</a>
                            <a class="nav-item nav-link active" href="#">Reportes</a>
                            <li class="nav-item dropdown active">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Configuración
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('vaccinations.index') }}">Vacunas</a>
                                    <a class="dropdown-item" href="{{ route('dewormers.index') }}">Desparasitaciones</a>
                                    <a class="dropdown-item" href="{{ route('vitamins.index') }}">Vitaminas</a>
                                    <a class="dropdown-item" href="{{ route('breeds.index') }}">Razas</a>
                                    <a class="dropdown-item" href="{{ route('agegroups.index') }}">Grupos Etarios</a>
                                    <a class="dropdown-item" href="{{ route('diagnostics.index') }}">Diagnósticos</a>
                                    <a class="dropdown-item" href="{{ route('causes.index') }}">Causas</a>
                                    <a class="dropdown-item" href="{{ route('treatments.index') }}">Tratamientos</a>
                                    <a class="dropdown-item" href="{{ route('veterinarians.index') }}">Veterinarios</a>
                                    <a class="dropdown-item" href="{{ route('responsibles.index') }}">Responsables</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container py-4 mt-5">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Languaje DatePicker-->
    <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format: "dd/mm/yyyy",
            language: "es",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom auto",
            startDate: "01/01/2000",
            forceParse: false,
            endDate: "todate()",
            todayBtn: true
        });
    </script>
    <!-- Cualquier otro script que se necesite dentro de la aplicación -->
     @yield('scripts')
</body>
</html>
