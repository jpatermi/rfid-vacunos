<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>RFID</title>
  </head>
  <body>
    @inject('areas', 'App\Area')
    <div class="container col-md-6">
      <h1>RFID</h1>
      <select class="custom-select" id="areas">
        @foreach($areas->get() as $area)
          <option value="{{ $area->id }}">{{ $area->name }}</option>
        @endforeach
      </select>
      <select class="custom-select mt-2" id="lct1">
      </select>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
//      $(document).ready(function() {
//        $("#areas").change(function(event) {
//          $("#lct1").empty();
//          $.get("/rfid/public/showbyarea/"+event.target.value+"", function(response, state){
//              console.log(response);
//              for(i = 0; i < response.length; i++){
//                $("#lct1").append("<option value='"+response[i].id+"'>"+response[i].name+"</option>");
//              }
//            });
//        });
//      });
      $("#areas").change(event => {
        $.get(`/rfid/public/showbyarea/${event.target.value}`, function(response, state){
          $("#lct1").empty();
          response.forEach(element => {
            $("#lct1").append(`<option values=${element.id}>${element.name}</option>}`)
          });
        });
      });
    </script>
  </body>
</html>
