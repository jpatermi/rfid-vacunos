<!DOCTYPE html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Datepicker</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Datepicker Files -->
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('datePicker/css/bootstrap-datepicker.standalone.css')}}">

</head>
<body>
<div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-md-4 col-md-offset-4">

                    <form action="/test/save" method="post">
                        <div class="form-group">
                            <label for="date">Fecha</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="date">
                                  <img class="material-icons pt-1 input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>

                </div>
            </div>
        </div>
</div>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Languaje DatePicker-->
    <script src="{{asset('datePicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>

<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true
    });
</script>
</body>
</html>