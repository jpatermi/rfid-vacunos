@extends('layouts.app')

@section('content')
    <div class="card">
      <div class="card-header font-weight-bold text-center">
        <h3>Aplicación masiva de {{ $label}}</h3>
      </div>
      <div class="card-body">
        @if($errors->any())
          <div class="alert alert-danger">
            <h6>Por favor corregir los errores abajo señalados:</h6>
          </div>
        @endif

        <form method = "POST" action="{{ route($model . '.store') }}">
          @csrf

          <div class="form-group">
              <label class="font-weight-bold" for="vacdewvit_idCreate">{{ $label }}:</label>
              <select class="form-control" id="vacdewvit_idCreate">
                @foreach($vaccinations as $vaccination)
                    <option value="{{ $vaccination->id }}">{{ $vaccination->name }}</option>
                  @endforeach
              </select>
            @if($errors->has('vaccination_id'))
              <p class="text-danger"><strong>{{ $errors->first('vaccination_id') }}</strong></p>
            @endif
          </div>

          <div class="form-group" @if($label == 'Examen') hidden @endif>
            <label class="font-weight-bold" for="dose">Dosis (cc):</label>
            <input type="text" class="form-control" name="dose" id="dose" placeholder="00.00" value="{{ old('dose') }}">
            @if($errors->has('dose'))
              <p class="text-danger"><strong>{{ $errors->first('dose') }}</strong></p>
            @endif
          </div>

          <div class="form-group">
              <label class="font-weight-bold" for="dateface">Fecha de aplicación:</label>
              <div class="input-group date">
                  <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface') }}">
                    <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
              </div>
              @if($errors->has('application_date'))
                <p class="text-danger"><strong>{{ $errors->first('application_date') }}</strong></p>
              @endif
          </div>

          <div class="form-group">
              <label class="font-weight-bold" for="ageGroup">Grupo Etario:</label>
              <select class="form-control" id="ageGroup">
                @foreach($ageGroups as $ageGroup)
                    <option value="{{ $ageGroup->id }}">{{ $ageGroup->name }}</option>
                  @endforeach
              </select>
          </div>

            <hr>

            <div class="form-group nav justify-content-center">
                <div class="col-md-10">
                    <div class="nav justify-content-center mb-2" id="tituloLista">
                        <!--<h3>Lista de Animales</h3>-->
                    </div>
                    <ul class="list-unstyled">
                        <li id="animal_id">

                        </li>
                    </ul>
                </div>
            </div>

            <hr>

          <!-- Textos ocultos que  vendrán en la sesión para el user_id y el farm_id -->
          {{--<input type="text" name="animal_id[]" id="animal_id" hidden value="{{ old('animal_id', $animal->id) }}">--}}
          <input type="text" name="application_date" id="application_date" hidden value="{{ old('application_date') }}">
          <!-- -->
          <div class="nav justify-content-center">
            <button type="submit" class="btn btn-primary">Agregar {{ $label }}</button>
          </div>
        </form>
      </div>
      <div class="card-footer">
        <a href="{{ route('animals.index') }}" class="btn-link btn">Regresar al Listado de Animales</a>
      </div>
    </div>
    <input type="text" name="nomCampoVacDewVit" id="nomCampoVacDewVit" hidden value="{{ $nomCampoVacDewVit }}">
@endsection

@section('scripts')
  <script>
    $.when( $.ready ).then(function() {
      var nomCampoVacDewVit = document.getElementById('nomCampoVacDewVit').value;
      var vacdewvit_idCreate = document.getElementById('vacdewvit_idCreate');

      vacdewvit_idCreate.name = nomCampoVacDewVit;

      $( "#ageGroup" ).trigger( "change" );
    });

    $("#ageGroup").change(event => {
        $("#tituloLista").empty();
        $("#tituloLista").append(`<h3>Animales del Grupo Etario: <em>${event.target.options[event.target.selectedIndex].text}</em>`);
        $("#animal_id").empty();
        //console.log(`/rfid/public/AgeGroupAnimals/${event.target.value}`);
        $.getJSON(`/rfid/public/AgeGroupAnimals/${event.target.value}`).done(function(response, state){
            response.forEach(element => {
                $("#animal_id").append(`<label><input type="checkbox" name="animal_id[]" value=${element.id} checked> ${element.animal_rfid}</label>`)
            });
        });
    });

    //### Se usa en el Create ###
    $("#dateface").change(function(event) {
      $( "#application_date" ).attr("value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
    });

    function convertDateFormatVZLA_to_USA(string) {
      var info = string.split('/');
      return info[2] + '-' + info[1] + '-' + info[0];
    };
  </script>
@endsection