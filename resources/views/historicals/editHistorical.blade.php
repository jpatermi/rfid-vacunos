@extends('layouts.app')

@section('content')
  <div class="justify-content-center">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
          <h3>Actualizar Histórico para el Animal:
            <small class="text-danger">{{ $animal->animal_rfid }}</small>
          </h3>
        </div>
      </div>
      <div class="card-body">
        @if($errors->any())
          <div class="alert alert-danger">
            <h6>Por favor corregir los errores abajo señalados:</h6>
          </div>
        @endif

        <form method = "POST" action="{{ route('historicals.update', $historical) }}">
          {{ method_field('PUT') }}
          @csrf
          <div class="form-row nav justify-content-center">
            <div class="form-group col-md-8">
                <label class="font-weight-bold" for="dateface">Fecha Medición:</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface', $historical->measurement_date) }}">
                      <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
                </div>
                @if($errors->has('measurement_date'))
                  <p class="text-danger"><strong>{{ $errors->first('measurement_date') }}</strong></p>
                @endif
            </div>
            <div class="form-group col-md-8">
              <label class="font-weight-bold" for="weight">Peso (Kgs):</label>
              <input type="text" class="form-control" name="weight" id="weight" placeholder="0.00" value="{{ old('weight', $historical->weight) }}">
              @if($errors->has('weight'))
                <p class="text-danger"><strong>{{ $errors->first('weight') }}</strong></p>
              @endif
            </div>
            <div class="form-group col-md-8">
              <label class="font-weight-bold" for="height">Altura (Cms):</label>
              <input type="text" class="form-control" name="height" id="height" placeholder="00" value="{{ old('height', $historical->height) }}">
              @if($errors->has('height'))
                <p class="text-danger"><strong>{{ $errors->first('height') }}</strong></p>
              @endif
            </div>
          </div>
          <!-- Textos ocultos que  vendrán en la sesión para el animal_id y el measurement_date -->
          <input type="text" name="animal_id" id="animal_id" hidden value="{{ old('animal_id', $animal->id) }}">
          <input type="text" name="measurement_date" id="measurement_date" hidden value="{{ $historical->measurement_date }}">
          <!-- -->
          <div class="form-row nav justify-content-center">
            <button type="submit" class="btn btn-primary">Actualizar Histórico</button>
          </div>
        </form>
      </div>
      <div class="card-footer">
        <a href="{{ route('historicals.show', $animal->id) }}" class="btn-link btn">Regresar al listado de Históricos</a>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
          $( "#dateface" ).attr( "value", convertDateFormatUSA_to_VZLA(`${dateface.value}`));
        });

        function convertDateFormatUSA_to_VZLA(string) {
          var info = string.split('-');
          return info[2].substring(0,2) + '/' + info[1] + '/' + info[0];
        };

        $("#dateface").change(function(event) {
          $( "#measurement_date" ).attr("value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
        });

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
    </script>
@endsection
