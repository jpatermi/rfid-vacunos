<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <!-- Datepicker Files -->
        <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
        <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker.standalone.css')}}">
        <!-- Iconos Material Design -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="icon" href="{{ asset('img/cow-256.png') }}">
        <title>RFID</title>
    </head>
    <body>
    <header>
        <nav class="navbar fixed-top navbar-dark bg-dark navbar-expand-lg">
            <a class="navbar-brand" href="{{ url("/breeds") }}">
                <img src="{{ asset('img/cow-256.png') }}" width="30" height="30" class="d-inline-block align-top" alt="Logo RFID">
                RFID
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <div class="navbar-nav ml-auto mr-auto text-center">
                  <a class="nav-item nav-link active" href="#">Registro de Animales</a>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Registro Sanitario
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                      <a class="dropdown-item" href="#">Vacunas</a>
                      <a class="dropdown-item" href="#">Desparasitaciones</a>
                      <a class="dropdown-item" href="#">Vitaminas</a>
                    </div>
                  </li>
                  <a class="nav-item nav-link" href="#">Registro de Producción</a>
                  <a class="nav-item nav-link" href="#">Reportes</a>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Configuración
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                      <a class="dropdown-item" href="#">Vacunas</a>
                      <a class="dropdown-item" href="#">Desparasitaciones</a>
                      <a class="dropdown-item" href="#">Vitaminas</a>
                    </div>
                  </li>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-5 p-3" role="main">

      @yield('content')

    </main>
    {{--<footer class="card-footer text-muted text-center">
      <div class="container">
        <span class="text-muted">Contenido del Pie de Página.</span>
      </div>
    </footer>--}}
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
    @yield('script_area_lct1_lct2')
    @yield('script_fecha')
    @yield('script_fecha_edit')
   </body>
</html>
