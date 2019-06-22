@extends('layouts.app')

@section('content')
  <div class="justify-content-center">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-end p-2">
          <h3>Producción para el Animal:
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

        <form method = "POST" action="{{ route('productions.store') }}">
          @csrf
          <div class="form-row nav justify-content-center">
            <div class="form-group col-md-8">
                <label class="font-weight-bold" for="dateface">Fecha de Producción:</label>
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" name="dateface" id="dateface" placeholder="dd/mm/aaaa" autocomplete="off" value="{{ old('dateface') }}">
                      <img class="input-group-addon" src="{{ asset('img/ico/baseline-calendar_today-24px.svg') }}" alt="Calendario">
                </div>
                @if($errors->has('production_date'))
                  <p class="text-danger"><strong>{{ $errors->first('production_date') }}</strong></p>
                @endif
            </div>
            <div class="form-group col-md-8">
              <label class="font-weight-bold" for="colostrum">Calostro (ml):</label>
              <input type="text" class="form-control" name="colostrum" id="colostrum" placeholder="0.00" value="{{ old('colostrum') }}">
              @if($errors->has('colostrum'))
                <p class="text-danger"><strong>{{ $errors->first('colostrum') }}</strong></p>
              @endif
            </div>
            <div class="form-group col-md-8">
              <label class="font-weight-bold" for="milk">Leche (ml):</label>
              <input type="text" class="form-control" name="milk" id="milk" placeholder="0.00" value="{{ old('milk') }}">
              @if($errors->has('milk'))
                <p class="text-danger"><strong>{{ $errors->first('milk') }}</strong></p>
              @endif
            </div>
          </div>
          <!-- Textos ocultos que  vendrán en la sesión para el animal_id y el production_date -->
          <input type="text" name="animal_id" id="animal_id" hidden value="{{ old('animal_id', $animal->id) }}">
          <input type="text" name="production_date" id="production_date" hidden value="{{ old('production_date') }}">
          <!-- -->
          <div class="form-row nav justify-content-center">
            <button type="submit" class="btn btn-primary">Agregar Producción</button>
          </div>
        </form>
      </div>
      <div class="card-footer">
        <a href="{{ route('productions.show', $animal->id) }}" class="btn-link btn">Regresar al listado de Producción</a>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
    <script>
        $("#dateface").change(function(event) {
          $( "#production_date" ).attr("value", convertDateFormatVZLA_to_USA(`${dateface.value}`));
        });

        function convertDateFormatVZLA_to_USA(string) {
          var info = string.split('/');
          return info[2] + '-' + info[1] + '-' + info[0];
        };
    </script>
@endsection
